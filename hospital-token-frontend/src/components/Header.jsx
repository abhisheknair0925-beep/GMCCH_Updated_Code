import React from 'react';
import { Link } from 'react-router-dom';
import { Phone, Clock, Calendar, Menu } from 'lucide-react';

const Header = () => {
  return (
    <header className="w-full">
      {/* 1. Header Top */}
      <div className="header-top text-black">
        <div className="container flex justify-center items-center py-2">
          <span className="text-[15px] font-bold tracking-tight">Medical College Chest Hospital Thrissur</span>
        </div>
      </div>

      {/* 2. Main Navbar */}
      <nav className="navbar py-4">
        <div className="container flex justify-between items-center">
          <Link to="/" className="flex items-center gap-4">
            <img src="/images/logo.png" alt="MCC Logo" className="h-16 w-auto" />
            <div className="hidden sm:block">
              <h1 className="m-0 text-3xl font-black text-black leading-none">MCC HOSPITAL</h1>
              <p className="m-0 text-[11px] text-primary font-bold tracking-[0.2em] uppercase">For A Better Treatment</p>
            </div>
          </Link>
          
          <div className="hidden lg:flex items-center gap-10">
            <Link to="/" className="font-bold text-[13px] hover:text-primary transition-colors text-primary">HOME</Link>
            <Link to="/#about" className="font-bold text-[13px] hover:text-primary transition-colors text-secondary-text">ABOUT US</Link>
            <Link to="/#contact" className="font-bold text-[13px] hover:text-primary transition-colors text-secondary-text">CONTACT US</Link>
          </div>

          <button className="lg:hidden p-2 text-primary">
            <Menu size={24} />
          </button>
        </div>
      </nav>

      {/* 3. Header Down */}
      <div className="header-down py-3">
        <div className="container flex flex-wrap justify-between items-center gap-6">
          <div className="flex items-center gap-10 text-sm font-bold text-white">
            <span className="flex items-center gap-3">
              <Phone size={18} fill="white" />
              <div>
                <p className="m-0 leading-none">Call 0487-2200610</p>
                <p className="m-0 text-[10px] opacity-80 uppercase">Ask for Doctor</p>
              </div>
            </span>
            <span className="hidden md:flex items-center gap-3">
              <Clock size={18} fill="white" />
              <div>
                <p className="m-0 leading-none">Open Hours</p>
                <p className="m-0 text-[10px] opacity-80 uppercase">Mon-Sun (24 hrs)</p>
              </div>
            </span>
            <span className="hidden lg:flex items-center gap-3">
              <Calendar size={18} fill="white" />
              <div>
                <p className="m-0 leading-none">For an Appointment</p>
                <p className="m-0 text-[10px] opacity-80 uppercase">Book Now</p>
              </div>
            </span>
          </div>
          <Link to="/login" className="bg-white text-primary hover:bg-secondary hover:text-white transition-all py-2 px-8 rounded-md text-[13px] font-black uppercase shadow-xl">
            Book Now
          </Link>
        </div>
      </div>
    </header>
  );
};

export default Header;
