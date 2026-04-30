package gmcch.vast.token.Doctor;

import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;


import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import gmcch.vast.token.MainActivity;
import gmcch.vast.token.ModelClass.DoctorlistModelclass;
import gmcch.vast.token.R;


public class DoctorLogin extends AppCompatActivity {

    private static final String SHARED_PREF_NAME = "DOCTORDETAILS";
    private static final String DOC_ID = "id";
    private static final String UNIT_ID = "Unitid";
    private static final String DOC_NAME = "docname";

    EditText usernameEditText,passwordEditText;
    Button loginButton;
    ProgressBar loadingProgressBar;

    DoctorlistModelclass doctorlistModelclass;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_doctor_login);

        if(gmcch.vast.token.SharedPrefManager.getInstance(this).isLoggedIn()){
            finish();
            startActivity(new Intent(this, DoctorHomePage.class));
        }

        usernameEditText = findViewById(R.id.dl_username);
        passwordEditText = findViewById(R.id.dl_password);
        loginButton = findViewById(R.id.dl_login);
        loadingProgressBar = findViewById(R.id.dl_loading);

        loginButton.setOnClickListener(view -> {

            if (!validate()) {
                Toast.makeText(getApplicationContext(), "Enter valid data!", Toast.LENGTH_LONG).show();
            } else
                login();
        });



    }

    private void login() {
        loadingProgressBar.setVisibility(View.VISIBLE);
        String user1 = usernameEditText.getText().toString();
        String pass1 = passwordEditText.getText().toString();

        RequestQueue requestQueue= Volley.newRequestQueue(getApplicationContext());
        StringRequest stringRequest=new StringRequest(Request.Method.POST, gmcch.vast.token.Config.Doctor_login, response -> {

            String apiResponse = response;
            Log.d("DoctorLogin", "API Response: " + apiResponse);
            loadingProgressBar.setVisibility(View.INVISIBLE);

            if (apiResponse != null && !apiResponse.isEmpty()) {

                try {

                    JSONObject jsonObject = new JSONObject(apiResponse);
                    String error = jsonObject.optString("error", "true");
                    if (error.contains("true")) {
                        Toast.makeText(DoctorLogin.this, jsonObject.optString("message", "Login Failed"), Toast.LENGTH_SHORT).show();
                    } else if (error.contains("false")) {

                        JSONObject details = jsonObject.getJSONObject("details");

                        doctorlistModelclass=new DoctorlistModelclass(details.getInt("id"),details.getString("Name"),details.getString("Unitid"));

                        gmcch.vast.token.SharedPrefManager.getInstance(getApplicationContext()).doctorlogin(doctorlistModelclass);

                        Intent intent = new Intent(getApplicationContext(), DoctorHomePage.class);
                        startActivity(intent);
                        finish();
                    }
                } catch (Exception e) {
                    Log.e("DoctorLogin", "JSON Parsing error", e);
                    Toast.makeText(DoctorLogin.this, "Data format error", Toast.LENGTH_SHORT).show();
                }
            } else {
                Toast.makeText(DoctorLogin.this, "Empty response from server", Toast.LENGTH_SHORT).show();
            }
        }, error -> {
            loadingProgressBar.setVisibility(View.INVISIBLE);
            Log.e("DoctorLogin", "Volley error", error);
            Toast.makeText(DoctorLogin.this, "Check your Internet Connection", Toast.LENGTH_SHORT).show();
        })
        {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> parmas = new HashMap<>();

                //here we pass params
                parmas.put("regno", user1);
                parmas.put("pass", pass1);

                Log.d("DoctorLogin", "Request Parameters: " + parmas.toString());
                return parmas;
            }
        };
        stringRequest.setRetryPolicy(new DefaultRetryPolicy(
                15000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        requestQueue.add(stringRequest);
    }

    private boolean validate() {

        String userid = usernameEditText.getText().toString();
        final String password = passwordEditText.getText().toString();


        if (TextUtils.isEmpty(userid)) {
            usernameEditText.setError("Email is required");
            return false;
        }

        if (TextUtils.isEmpty(password)) {
            passwordEditText.setError("Password is required");
            return false;
        }

        return true;

    }
    @Override
    public void onBackPressed() {
        DoctorLogin.super.onBackPressed();
        Intent intent = new Intent(getApplicationContext(), MainActivity.class);
        startActivity(intent);
    }
}