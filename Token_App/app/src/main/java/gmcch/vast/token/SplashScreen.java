package gmcch.vast.token;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

public class SplashScreen extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash_screen);

        int SPLASH_TIME_OUT = 2000;
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                checkFirstRun();
            }
        }, SPLASH_TIME_OUT);

    }


    private void checkFirstRun() {
        boolean isFirstRun = getSharedPreferences("PREFERENCE", MODE_PRIVATE).getBoolean("isFirstRun", true);
        if (isFirstRun) {
            AlertDialog.Builder alertbox = new AlertDialog.Builder(SplashScreen.this);
            alertbox.setCancelable(false);
            alertbox.setTitle("Terms & Conditions");
            alertbox.setMessage("Welcome to MCCH Token!\n" +"\n" +
                    "These terms and conditions outline the rules and regulations for the use of MCCH Token.\n" + "\n" +
                    "Accounts and membership\n" +"\n"+
                    "If you create an account in the Mobile Application, you are responsible for maintaining the security of your account and you are fully responsible for all activities that occur under the account and any other actions taken in connection with it. We may, but have no obligation to, monitor and review new accounts before you may sign in and start using the Services. Providing false contact information of any kind may result in the termination of your account. You must immediately notify us of any unauthorized uses of your account or any other breaches of security. We will not be liable for any acts or omissions by you, including any damages of any kind incurred as a result of such acts or omissions\n" + "\n"+
                    "Links to other resources\n" + "\n"+
                    "Although the Mobile Application and Services may link to other resources (such as websites, mobile applications, etc.), we are not, directly or indirectly, implying any approval, association, sponsorship, endorsement, or affiliation with any linked resource. unless specifically stated herein. Some of the links in the Mobile Application may be 'affiliate links. This means if you click on the link and purchase an item, the Operator will receive an affiliate commission. We are not responsible for examining or evaluating, and we do not warrant the offerings of, any businesses or individuals or the content of their resources. We do not assume any responsibility or liability for the actions, products, services, and content of any other third parties. You should carefully review the legal statements and other conditions of use of any resource which you access through a link in the Mobile Application and Services Your linking to any other off-site resources is at your own risk.\n" + "\n"+
                    "Changes and amendments\n" +"\n" +
                    "We reserve the right to modify this Agreement or its terms relating to the Mobile Application and Services at any time, effective upon posting of an updated version of this Agreement in the Mobile Application. When we do, we will revise the updated date at the bottom of this page. Continued use of the Mobile Application and Services after any such changes shall constitute your consent to such changes.\n" + "\n" +
                    "Acceptance of these terms\n" +"\n" +
                    "You acknowledge that you have read this Agreement and agree to all its terms and conditions. By accessing and using the Mobile Application and Services you agree to be bound by this Agreement. If you do not agree to abide by the terms of this Agreement, you are not authorized to access or use the Mobile Application and Services. This terms and conditions policy was created with the help of the terms and conditions generator at https://www.websitepolicies.com/terms-and-conditions-generator\n" + "\n" +
                    "Contacting us\n" +"\n" +
                    "If you would like to contact us to understand more about this\n"+
                    "Agreement or wish to contact us concerning any matter relating to it, you may do so via the contact form.\n" + "\n" +
                    "This document was last updated on June 4, 2021"
                   );
            alertbox.setNegativeButton("I Agree", (dialog, which) -> {
                getSharedPreferences("PREFERENCE", MODE_PRIVATE)
                        .edit()
                        .putBoolean("isFirstRun", false)
                        .apply();
                Intent intent = new Intent(getApplicationContext(), MainActivity.class);
                startActivity(intent);
                finish();
            });
            alertbox.setPositiveButton("I dont Agree", (arg0, arg1) -> {
                finishAffinity();
                System.exit(0);
            });
            alertbox.show();


        } else {
            Intent intent = new Intent(getApplicationContext(), MainActivity.class);
            startActivity(intent);
            finish();
        }
    }


}