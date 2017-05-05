//
//  StartViewController.swift
//  Kanairi
//
//  Created by Kyle Wilson 4-1-17
//
//

import UIKit
import StaticDataTableViewController
import MetaWear
import MessageUI
import Bolts
import MBProgressHUD
import iOSDFULibrary
import MapKit
import CoreLocation

extension String {
    var drop0xPrefix:          String { return hasPrefix("0x") ? String(characters.dropFirst(2)) : self }
}

class DeviceDetailViewController: StaticDataTableViewController, DFUServiceDelegate, DFUProgressDelegate, CLLocationManagerDelegate, LoggerDelegate, DFUPeripheralSelectorDelegate {
    var device: MBLMetaWear!
    private var currentCoordinate: CLLocationCoordinate2D?
    
    @IBOutlet weak var connectionSwitch: UISwitch!
    @IBOutlet weak var connectionStateLabel: UILabel!
    
    @IBOutlet var allCells: [UITableViewCell]!
    
    @IBOutlet var infoAndStateCells: [UITableViewCell]!

    @IBOutlet weak var batteryLevelLabel: UILabel!
    

    @IBOutlet weak var map: MKMapView!
   
    @IBOutlet weak var webview: UIWebView!
    
   
    @IBOutlet weak var accelerometerBMI160Cell: UITableViewCell!
    @IBOutlet weak var accelerometerBMI160Scale: UISegmentedControl!
    @IBOutlet weak var accelerometerBMI160StartStream: UIButton!
    @IBOutlet weak var accelerometerBMI160StopStream: UIButton!
    @IBOutlet weak var accelerometerBMI160Graph: APLGraphView!
    var accelerometerBMI160Data = [MBLAccelerometerData]()
    
    
   
   
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    ///////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////
    // Map Section
    
    
    
    
    
    let manager = CLLocationManager()
    
    
    
    
    
    func locationManager(_ manager: CLLocationManager, didUpdateLocations locations: [CLLocation]) {
        
    
        let location = locations[0]
        
        let span:MKCoordinateSpan = MKCoordinateSpanMake(0.01, 0.01)
        
        let myLocation:CLLocationCoordinate2D = CLLocationCoordinate2DMake(location.coordinate.latitude, location.coordinate.longitude)
        
        let region:MKCoordinateRegion = MKCoordinateRegionMake(myLocation, span)
        
        map.setRegion(region, animated: true)
        
        self.map.showsUserLocation = true
        
        currentCoordinate = manager.location?.coordinate
        
        
        //print(currentCoordinate?.latitude ?? 0)
        
    }
    
    
    
    
    
    
    
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        
        
        manager.delegate = self
        manager.desiredAccuracy = kCLLocationAccuracyBest
        manager.requestAlwaysAuthorization()
        manager.startUpdatingLocation()
        
        manager.pausesLocationUpdatesAutomatically = false
        if #available(iOS 9.0, *) {
            manager.allowsBackgroundLocationUpdates = true
        } else {
            // Fallback on earlier versions
        }
        

        
        
        let url = URL(string: "http://159.203.251.4/index.php");
        let request = URLRequest(url: url!);
        webview.loadRequest(request);
    
        
    }
    
    
    
    
    func data_request(_ url:String)
    {
        
        
        let url:NSURL = NSURL(string: url)!
        let session = URLSession.shared
        
        let request = NSMutableURLRequest(url: url as URL)
        request.httpMethod = "POST"
        
        
        let paramString = "data=\(currentCoordinate?.latitude, currentCoordinate?.longitude)"
        request.httpBody = paramString.data(using: String.Encoding.utf8)
        
        let task = session.dataTask(with: request as URLRequest) {
            (
            data, response, error) in
            
            guard let _:NSData = data as NSData?, let _:URLResponse = response, error == nil else {
                print("error")
                return
            }
            
            if let dataString = NSString(data: data!, encoding: String.Encoding.utf8.rawValue)
            {
                print(dataString)
            }
        }
        
        task.resume()
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////
    // Nuts and Bolts
    
    
    
    
    var streamingEvents: Set<NSObject> = [] // Can't use proper type due to compiler seg fault
    var isObserving = false {
        didSet {
            if self.isObserving {
                if !oldValue {
                    self.device.addObserver(self, forKeyPath: "state", options: .new, context: nil)
                }
            } else {
                if oldValue {
                    self.device.removeObserver(self, forKeyPath: "state")
                }
            }
        }
    }
    var hud: MBProgressHUD!
    
    var controller: UIDocumentInteractionController!
    var initiator: DFUServiceInitiator?
    var dfuController: DFUServiceController?
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        
        // Use this array to keep track of all streaming events, so turn them off
        // in case the user isn't so responsible
        streamingEvents = []
        // Hide every section in the beginning
        hideSectionsWithHiddenRows = true
        cells(self.allCells, setHidden: true)
        reloadData(animated: false)
        // Write in the 2 fields we know at time zero
        connectionStateLabel.text! = nameForState()
        // Listen for state changes
        isObserving = true
        // Start off the connection flow
        connectDevice(true)
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        isObserving = false
        for obj in streamingEvents {
            if let event = obj as? MBLEvent<AnyObject> {
                event.stopNotificationsAsync()
            }
        }
        streamingEvents.removeAll()
    }

    override func observeValue(forKeyPath keyPath: String?, of object: Any?, change: [NSKeyValueChangeKey : Any]?, context: UnsafeMutableRawPointer?) {
        OperationQueue.main.addOperation {
            self.connectionStateLabel.text! = self.nameForState()
            if self.device.state == .disconnected {
                self.deviceDisconnected()
            }
        }
    }
    
    func nameForState() -> String {
        switch device.state {
        case .connected:
            return device.programedByOtherApp ? "Connected (LIMITED)" : "Connected"
        case .connecting:
            return "Connecting"
        case .disconnected:
            return "Disconnected"
        case .disconnecting:
            return "Disconnecting"
        case .discovery:
            return "Discovery"
        }
    }
    
    func logCleanup(_ handler: @escaping MBLErrorHandler) {
        // In order for the device to actaully erase the flash memory we can't be in a connection
        // so temporally disconnect to allow flash to erase.
        isObserving = false
        device.disconnectAsync().continueOnDispatch { t in
            self.isObserving = true
            guard t.error == nil else {
                return t
            }
            return self.device.connect(withTimeoutAsync: 15)
        }.continueOnDispatch { t in
            handler(t.error)
            return nil
        }
    }
    
    func showAlertTitle(_ title: String, message: String) {
        let alertController = UIAlertController(title: title, message: message, preferredStyle: .alert)
        alertController.addAction(UIAlertAction(title: "Okay", style: .default, handler: nil))
        self.present(alertController, animated: true, completion: nil)
    }
    
    func deviceDisconnected() {
        connectionSwitch.setOn(false, animated: true)
        cells(self.allCells, setHidden: true)
        reloadData(animated: true)

        
    }
    
    func deviceConnected() {
        connectionSwitch.setOn(true, animated: true)
        // Perform all device specific setup
        if let mac = device.settings?.macAddress {
            mac.readAsync().success { result in
                print("ID: \(self.device.identifier.uuidString) MAC: \(result.value)")
            }
        } else {
            print("ID: \(device.identifier.uuidString)")
        }
        // We always have the info and state features
        cells(self.infoAndStateCells, setHidden: false)
        
        // Automaticaly send off some reads
        device.readBatteryLifeAsync().success { result in
            self.batteryLevelLabel.text = result.stringValue
        }
        
        
        
      
        
        
        
        
        
        
        
        
        
        
        // reset app
        
        /////////////////////////////////////////////////////////////////
        
        // Only allow LED module if the device is in use by other app
        if device.programedByOtherApp {
            if UserDefaults.standard.object(forKey: "ihaveseenprogramedByOtherAppmessage") == nil {
                UserDefaults.standard.set(1, forKey: "ihaveseenprogramedByOtherAppmessage")
                UserDefaults.standard.synchronize()
                self.showAlertTitle("WARNING", message: "You have connected to a device being used by another app. If you wish to take control please press 'Unbind Others' and then turn the connection switch to the on position.")
            }
            reloadData(animated: true)
            return
        }
        
        
        
        
         if (device.accelerometer is MBLAccelerometerBMI160) {
            cell(accelerometerBMI160Cell, setHidden: false)
            if device.accelerometer!.dataReadyEvent.isLogging() {
                accelerometerBMI160StartStream.isEnabled = false
                accelerometerBMI160StopStream.isEnabled = false
            } else {
                accelerometerBMI160StartStream.isEnabled = true
                accelerometerBMI160StopStream.isEnabled = false
            }
        }         
       
       
    
        
       
    
       
        
        // Make the magic happen!
        reloadData(animated: true)
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////
    // Reset Device and other actions
    
    
    
    @IBAction func resetDevicePressed(_ sender: Any) {
        // Resetting causes a disconnection
        deviceDisconnected()
        // Preform the soft reset
        device.resetDevice()
    }
    
    

    
    func connectDevice(_ on: Bool) {
        let hud = MBProgressHUD.showAdded(to: UIApplication.shared.keyWindow!, animated: true)
        if on {
            hud.label.text = "Connecting..."
            device.connect(withTimeoutAsync: 15).continueOnDispatch { t in
                if (t.error?._domain == kMBLErrorDomain) && (t.error?._code == kMBLErrorOutdatedFirmware) {
                    hud.hide(animated: true)
                    return nil
                }
                hud.mode = .text
                if t.error != nil {
                    self.showAlertTitle("Error", message: t.error!.localizedDescription)
                    hud.hide(animated: false)
                } else {
                    self.deviceConnected()
                    
                    hud.label.text! = "Connected!"
                    hud.hide(animated: true, afterDelay: 0.5)
                }
                return nil
            }
        } else {
            hud.label.text = "Disconnecting..."
            device.disconnectAsync().continueOnDispatch { t in
                self.deviceDisconnected()
                hud.mode = .text
                if t.error != nil {
                    self.showAlertTitle("Error", message: t.error!.localizedDescription)
                    hud.hide(animated: false)
                }
                else {
                    hud.label.text = "Disconnected!"
                    hud.hide(animated: true, afterDelay: 0.5)
                }
                return nil
            }
        }
    }
    
    @IBAction func connectionSwitchPressed(_ sender: Any) {
        connectDevice(connectionSwitch.isOn)
    }
    
    
    @IBAction func readBatteryPressed(_ sender: Any) {
        device.readBatteryLifeAsync().success { result in
            self.batteryLevelLabel.text = result.stringValue
        }.failure { error in
            self.showAlertTitle("Error", message: error.localizedDescription)
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    ////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////
    // Accelerometer

    
    
    
    
    
    
    
    
    
    func updateAccelerometerBMI160Settings() {
        let accelerometerBMI160 = self.device.accelerometer as! MBLAccelerometerBMI160
     
       
            accelerometerBMI160.fullScaleRange = .range16G
            accelerometerBMI160Graph.fullScale = 16
    

        
    }
    
    
    
    
    

    
    
    
    
    @IBAction func accelerometerBMI160StartStreamPressed(_ sender: Any) {
        accelerometerBMI160StartStream.isEnabled = false
        accelerometerBMI160StopStream.isEnabled = true
        updateAccelerometerBMI160Settings()
        device.led?.setLEDOnAsync(false, withOptions: 1)
        
    
        
        
    
        
       
        
        
        var array = [MBLAccelerometerData]() /* capacity: 1000 */
        accelerometerBMI160Data = array
        streamingEvents.insert(device.accelerometer!.dataReadyEvent)
        device.accelerometer!.dataReadyEvent.startNotificationsAsync { (obj, error) in
            if let obj = obj {
                self.accelerometerBMI160Graph.addX(obj.x, y: obj.y, z: obj.z)
                array.append(obj)
                
            
            
                
                
                 //print(obj)
                
                
                if obj.x > 15.9 {
                    
                    //print("Dial 911")
                    
                    self.device.led?.flashColorAsync(UIColor.red, withIntensity: 1.0)
                    
                    self.data_request("http://159.203.251.4/index2.php")
                    
                    self.accelerometerBMI160StartStream.isEnabled = true
                    self.accelerometerBMI160StopStream.isEnabled = false
                    self.streamingEvents.remove(self.device.accelerometer!.dataReadyEvent)
                    self.device.accelerometer!.dataReadyEvent.stopNotificationsAsync()
                    
                    print("X 911")
                    
                }
                
                if obj.y > 15.9 {
                    
                    //print("Dial 911")
                    
                    self.device.led?.flashColorAsync(UIColor.red, withIntensity: 1.0)
                    
                    self.data_request("http://159.203.251.4/index2.php")
                    
                    self.accelerometerBMI160StartStream.isEnabled = true
                    self.accelerometerBMI160StopStream.isEnabled = false
                    self.streamingEvents.remove(self.device.accelerometer!.dataReadyEvent)
                    self.device.accelerometer!.dataReadyEvent.stopNotificationsAsync()
                    
                    print("Y 911")
                    
                }
                
                if obj.z > 15.9 {
                    
                    //print("Dial 911")
                    
                    self.device.led?.flashColorAsync(UIColor.red, withIntensity: 1.0)
                    
                    self.data_request("http://159.203.251.4/index2.php")
                    
                    self.accelerometerBMI160StartStream.isEnabled = true
                    self.accelerometerBMI160StopStream.isEnabled = false
                    self.streamingEvents.remove(self.device.accelerometer!.dataReadyEvent)
                    self.device.accelerometer!.dataReadyEvent.stopNotificationsAsync()
                    
                    print("Z 911")
                    
                }
                
            }
            
        }
        
        
        streamingEvents.insert(device.mechanicalSwitch!.switchUpdateEvent)
        device.mechanicalSwitch!.switchUpdateEvent.startNotificationsAsync { (obj, error) in
            if let obj = obj {
                
                print(obj.value)
                
                self.device.led?.setLEDOnAsync(false, withOptions: 1)
            
                if obj.value.boolValue{
                    
                    self.device.led?.setLEDColorAsync(UIColor.red, withIntensity: 1.0)
                    
                    
                    self.data_request("http://159.203.251.4/index2.php")
                    
                    print("press 911")

                    
                }

                
            }
        }
  
        
    }
    
    
    
    
    
    
    
    @IBAction func accelerometerBMI160StopStreamPressed(_ sender: Any) {
        accelerometerBMI160StartStream.isEnabled = true
        accelerometerBMI160StopStream.isEnabled = false
        device.led?.setLEDOnAsync(false, withOptions: 1)
        streamingEvents.remove(device.accelerometer!.dataReadyEvent)
        device.accelerometer!.dataReadyEvent.stopNotificationsAsync()
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    ///////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////
    // Nuts and Bolts
    
    
    
   
    // MARK: - DFU Service delegate methods
    
    func dfuStateDidChange(to state: DFUState) {
        if state == .completed {
            hud?.mode = .text
            hud?.label.text = "Success!"
            hud?.hide(animated: true, afterDelay: 2.0)
        }
    }
    
    func dfuError(_ error: DFUError, didOccurWithMessage message: String) {
        print("Firmware update error \(error): \(message)")
        
        let alertController = UIAlertController(title: "Update Error", message: "Please re-connect and try again, if you can't connect, try MetaBoot Mode to recover.\nError: \(message)", preferredStyle: .alert)
        alertController.addAction(UIAlertAction(title: "OK", style: .default, handler: nil))
        present(alertController, animated: true, completion: nil)
        
        hud?.hide(animated: true)
    }
    
    func dfuProgressDidChange(for part: Int, outOf totalParts: Int, to progress: Int,
                              currentSpeedBytesPerSecond: Double, avgSpeedBytesPerSecond: Double) {
        hud?.progress = Float(progress) / 100.0
    }
    
    func logWith(_ level: LogLevel, message: String) {
        if level.rawValue >= LogLevel.application.rawValue {
            print("\(level.name()): \(message)")
        }
    }
    
    func select(_ peripheral:CBPeripheral, advertisementData: [String : AnyObject], RSSI: NSNumber) -> Bool {
        return peripheral.identifier == device.identifier
    }
    
    func filterBy(hint dfuServiceUUID: CBUUID) -> [CBUUID]? {
        return nil
    }
}
