import React from 'react';
import Header from '../components/Header';
import Footer from '../components/Footer';
import { ArrowRight, Star, ShieldCheck, HeartPulse } from 'lucide-react';

const Home = () => {
  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      
      <main className="flex-grow">
        {/* Hero Section */}
        <section className="relative overflow-hidden bg-[#fff] min-h-[600px] flex items-center">
          <div className="container grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
            <div className="reveal">
              <span className="badge badge-primary mb-4">Quality Care Always</span>
              <h1 className="text-5xl lg:text-7xl font-black mb-6 leading-tight">
                For A Better <br />
                <span className="text-primary">Treatment</span>
              </h1>
              <p className="text-xl text-secondary mb-8 max-w-lg">
                Medical College Chest Hospital Thrissur provides world-class medical facilities and professional care for all respiratory and chest-related health needs.
              </p>
              <div className="flex gap-4">
                <button className="btn btn-primary px-8 py-4 text-lg">
                  Book Appointment <ArrowRight size={20} className="ml-2" />
                </button>
                <button className="btn btn-secondary px-8 py-4 text-lg">
                  Learn More
                </button>
              </div>
              
              <div className="mt-12 flex items-center gap-6">
                <div className="flex -space-x-4">
                  {[1, 2, 3, 4].map(i => (
                    <div key={i} className="w-12 h-12 rounded-full border-4 border-white overflow-hidden bg-gray-200">
                      <img src={`https://i.pravatar.cc/150?u=doc${i}`} alt="doc" />
                    </div>
                  ))}
                </div>
                <div>
                  <p className="m-0 font-bold text-text-primary">50+ Professional Doctors</p>
                  <div className="flex text-yellow-400 gap-1">
                    {[1, 2, 3, 4, 5].map(i => <Star key={i} size={14} fill="currentColor" />)}
                    <span className="text-xs text-secondary ml-2">(4.9/5 Rating)</span>
                  </div>
                </div>
              </div>
            </div>

            <div className="relative reveal" style={{ animationDelay: '0.2s' }}>
              <div className="absolute -inset-4 bg-primary/10 rounded-full blur-3xl"></div>
              <div className="relative rounded-3xl overflow-hidden shadow-2xl border-8 border-white">
                <img src="/images/doctor-banner.png" alt="Doctor Banner" className="w-full h-auto object-cover transform hover:scale-105 transition-transform duration-700" />
              </div>
              {/* Floating Cards */}
              <div className="absolute top-10 -left-10 glass-panel p-4 flex items-center gap-3 animate-bounce">
                <div className="bg-primary p-2 rounded-lg text-white"><ShieldCheck size={20} /></div>
                <div>
                  <p className="m-0 text-xs font-bold text-text-primary">Secured Systems</p>
                  <p className="m-0 text-[10px] text-secondary">Verified Facility</p>
                </div>
              </div>
              <div className="absolute bottom-10 -right-10 glass-panel p-4 flex items-center gap-3" style={{ animation: 'bounce 3s infinite' }}>
                <div className="bg-accent p-2 rounded-lg text-white"><HeartPulse size={20} /></div>
                <div>
                  <p className="m-0 text-xs font-bold text-text-primary">Emergency 24/7</p>
                  <p className="m-0 text-[10px] text-secondary">Ready to help</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* About Us Section */}
        <section id="about" className="section-padding bg-white">
          <div className="container">
            <h2 className="text-center text-4xl font-black text-primary uppercase mb-16 tracking-widest">About Us</h2>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
              <div className="reveal">
                <div className="rounded-xl overflow-hidden shadow-2xl">
                  <img src="/images/hospital-building.png" alt="Hospital Building" className="w-full h-auto" />
                </div>
              </div>
              
              <div className="reveal" style={{ animationDelay: '0.2s' }}>
                <p className="text-lg leading-relaxed text-black mb-6">
                  Medical college chest hospital was inaugurated on 1 April 1982 by the Governor of Kerala Jothi Venkatachalam. G. M. C, Thrissur had its humble beginning at Mannuthy. By March 1983, the institution had moved to its permanent site at Mulakunnathukavu, where the old buildings of the T.B. sanatorium were modified to accommodate the pre-clinical and the para-clinical departments as well as the administrative block.
                </p>
                <div className="w-full h-1 bg-black/10 mt-8"></div>
              </div>
            </div>
          </div>
        </section>

        {/* Call to Action */}
        <section className="section-padding bg-primary text-white text-center relative overflow-hidden">
          <div className="absolute top-0 left-0 w-full h-full opacity-10">
             {/* Abstract BG lines */}
             <svg width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 100 Q 500 0 1000 100" stroke="white" strokeWidth="2" />
                <path d="M0 200 Q 500 100 1000 200" stroke="white" strokeWidth="2" />
             </svg>
          </div>
          <div className="container relative z-10">
            <h2 className="text-4xl lg:text-5xl font-black mb-6 text-white">Experience Better Healthcare Today</h2>
            <p className="text-xl text-white/80 mb-10 max-w-2xl mx-auto">
              Join thousands of residents who trust MCC Hospital for their respiratory health and diagnostic needs.
            </p>
            <div className="flex justify-center gap-6">
              <button className="btn bg-white text-primary hover:bg-secondary hover:text-white px-10 py-4 text-lg font-bold shadow-2xl">
                Get Your Token Now
              </button>
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
};

export default Home;
