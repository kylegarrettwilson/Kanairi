//
//  LoginViewController.swift
//  Kanairi
//
//  Created by Kyle Wilson 4-1-17
//
//

import UIKit

class LoginViewController: UIViewController, UIWebViewDelegate{
    
    
    @IBOutlet weak var webview: UIWebView!

    @IBOutlet weak var boxView: UIView!
    
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        
        
        if #available(iOS 10.0, *) {
            var timer = Timer.scheduledTimer(withTimeInterval: 540, repeats: true) {
                (_) in
                let url = URL(string: "http://159.203.251.4/index.php");
                let request = URLRequest(url: url!);
                self.webview.loadRequest(request);
                
            }
        } else {
            // Fallback on earlier versions
        }
        
        
        
        
        let url = URL(string: "http://159.203.251.4/index.php");
        let request = URLRequest(url: url!);
        webview.loadRequest(request);
        
        
        
        navigationController?.setNavigationBarHidden(true, animated: animated)
        var preferredStatusBarStyle: UIStatusBarStyle {
            return .lightContent
        }
    }
    
    
    
    
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        navigationController?.setNavigationBarHidden(false, animated: animated)
        var preferredStatusBarStyle: UIStatusBarStyle {
            return .lightContent
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
