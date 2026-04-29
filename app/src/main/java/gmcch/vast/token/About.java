package gmcch.vast.token;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.webkit.WebView;
import android.widget.Button;

public class About extends AppCompatActivity {
    Button buttonCall, buttonNavigation, buttonEmail;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_about);
        buttonCall = findViewById(R.id.button_call);
        buttonNavigation = findViewById(R.id.button_navigate);
        buttonEmail = findViewById(R.id.button_mail);

        buttonCall.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent callIntent = new Intent(Intent.ACTION_DIAL);
                callIntent.setData(Uri.parse("tel: 04872200610"));
                callIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                startActivity(callIntent);

            }
        });


        buttonEmail.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(Intent.ACTION_SENDTO, Uri.parse("mailto:suptmcch@gmail.com")));
            }
        });

        buttonNavigation.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(android.content.Intent.ACTION_VIEW,
                        Uri.parse("http://maps.google.com/maps?daddr=10.61811002073491,76.19870342428928"));
                startActivity(intent);
            }
        });
        initializeUI();
    }

    private void initializeUI() {

        String iframe = "<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1960.7505665713638!2d76.198753!3d10.618122!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x88caab05ef15bcf4!2sMedical%20College%20Chest%20Hospital!5e0!3m2!1sen!2sin!4v1622789171083!5m2!1sen!2sin\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>";
        WebView googleMapWebView = (WebView) findViewById(R.id.googlemap_webView);
        googleMapWebView.getSettings().setJavaScriptEnabled(true);
        googleMapWebView.loadData(iframe, "text/html", "utf-8");
    }
}