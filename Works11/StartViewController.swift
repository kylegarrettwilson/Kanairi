//
//  StartViewController.swift
//  Kanairi
//
//  Created by Kyle Wilson 4-1-17
//
//

import UIKit

class StartViewController: UIViewController, UIWebViewDelegate{
    
    
 
    
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        
        
        sleep(5);
        
        
        
        
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
