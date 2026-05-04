import React from 'react';
import { MapPin, Mail, Phone, Facebook, Instagram, Twitter } from 'lucide-react';

const Footer = () => {
  return (
    <footer id="contact" className="bg-[#000] text-white pt-20 pb-10">
      <div className="container">
        <h2 className="text-center text-5xl font-black text-white uppercase mb-20 tracking-widest">Contact Us</h2>
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 mb-20 items-center">
          {/* Map Column */}
          <div className="reveal">
            <div className="rounded-sm overflow-hidden shadow-2xl h-[350px] border-4 border-white/20">
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3922.3789498263544!2d76.216667!3d10.516667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba7ee0f91882d25%3A0xc33e5c94294e1e0a!2sGovernment%20Medical%20College%20Thrissur!5e0!3m2!1sen!2sin!4v1620000000000!5m2!1sen!2sin" 
                width="100%" 
                height="100%" 
                style={{ border: 0 }} 
                allowFullScreen="" 
                loading="lazy"
                title="Hospital Map"
              ></iframe>
            </div>
          </div>

          {/* Contact Column */}
          <div className="reveal" style={{ animationDelay: '0.2s' }}>
            <div className="space-y-6">
              <div>
                <h4 className="text-primary text-xl font-black uppercase mb-4 tracking-widest">Address</h4>
                <div className="flex gap-4 items-start">
                  <MapPin size={18} className="text-primary mt-1 shrink-0" />
                  <p className="text-white font-medium text-sm leading-relaxed m-0">
                    Department of Radiotherapy Government Medical College Mulamkunnathukavu, Thrissur
                  </p>
                </div>
              </div>

              <div className="flex gap-4 items-center">
                <Mail size={18} className="text-primary shrink-0" />
                <p className="text-white font-medium text-sm m-0">suptmcch@gmail.com</p>
              </div>

              <div className="flex gap-4 items-center">
                <Phone size={18} className="text-primary shrink-0" />
                <p className="text-white font-medium text-sm m-0">0487 2200310</p>
              </div>

              <div className="flex gap-4 items-center pb-4">
                <Phone size={18} className="text-primary shrink-0" />
                <p className="text-white font-medium text-sm m-0">0487 2200610</p>
              </div>

              <Link to="/privacy" className="text-xs text-white/60 hover:text-primary transition-colors underline underline-offset-4">Privacy Policy</Link>
              
              <div className="mt-8 flex gap-4">
                <a href="#" className="w-10 h-10 flex items-center justify-center border border-white/20 hover:bg-primary hover:border-primary transition-all rounded-sm text-white">
                  <Facebook size={16} />
                </a>
                <a href="#" className="w-10 h-10 flex items-center justify-center border border-white/20 hover:bg-primary hover:border-primary transition-all rounded-sm text-white">
                  <span className="font-black text-xs">G+</span>
                </a>
              </div>
            </div>
          </div>
        </div>

        <hr className="border-white/10 mb-8" />
        
        <div className="text-center text-white/40 text-sm">
          <p className="mb-2">Copyright © 2021, MC Chest Hospital, Thrissur.</p>
          <p className="m-0">Developed with ❤️ by the Electronics and Communication Department of Vidya Academy of Science & Technology, Thrissur.</p>
          <div className="mt-4 space-x-4">
            <a href="#" className="hover:text-primary underline">Privacy Policy</a>
            <a href="#" className="hover:text-primary underline">Terms of Service</a>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
