package gmcch.vast.token.User;

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

import gmcch.vast.token.Config;
import gmcch.vast.token.MainActivity;
import gmcch.vast.token.ModelClass.Users;
import gmcch.vast.token.R;
import gmcch.vast.token.UserSharedPrefManager;


public class UserLogin extends AppCompatActivity {

    private static final String SHARED_PREF_NAME = "USERDETAILS";
    private static final String USER_ID = "id";
    private static final String USER_NAME = "username";
    private static final String USER_CR_NO = "crno";

    EditText usernameEditText;
    Button loginButton;
    ProgressBar loadingProgressBar;

    Users users;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_login);

        if(UserSharedPrefManager.getInstance1(this).isLoggedIn()){
            finish();
            startActivity(new Intent(this, UserHomePage.class));
        }

        usernameEditText = findViewById(R.id.ul_username);
        loginButton = findViewById(R.id.ul_login);
        loadingProgressBar = findViewById(R.id.ul_loading);

        loginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if (!validate()) {
                    Toast.makeText(getApplicationContext(), "Enter valid data!", Toast.LENGTH_LONG).show();
                } else
                    login();
            }
        });
    }

    private void login() {
        loadingProgressBar.setVisibility(View.VISIBLE);
        String user1 = usernameEditText.getText().toString();

        RequestQueue requestQueue= Volley.newRequestQueue(getApplicationContext());
        StringRequest stringRequest=new StringRequest(Request.Method.POST, Config.User_login, response -> {

            String apiResponse = response;
            Log.d("UserLogin", "API Response: " + apiResponse);
            loadingProgressBar.setVisibility(View.INVISIBLE);


            if (apiResponse != null && !apiResponse.isEmpty()) {
                users=new Users();
                try {
                    JSONObject jsonObject = new JSONObject(apiResponse);
                    String error = jsonObject.optString("error", "true");
                    if (error.contains("true")) {
                        Toast.makeText(UserLogin.this, jsonObject.optString("message", "Login Failed"), Toast.LENGTH_SHORT).show();
                    } else if (error.contains("false")) {
                        JSONObject details = jsonObject.getJSONObject("details");

                        users=new Users(details.getInt("id"),details.getString("Name"),details.getString("CR_number"));

                        UserSharedPrefManager.getInstance1(getApplicationContext()).userLogin(users);

                        Intent intent = new Intent(getApplicationContext(), UserHomePage.class);
                        startActivity(intent);
                        finish();
                    }
                } catch (Exception e) {
                    Log.e("UserLogin", "JSON Parsing error", e);
                    Toast.makeText(UserLogin.this, "Data format error", Toast.LENGTH_SHORT).show();
                }
            } else {
                Toast.makeText(UserLogin.this, "Empty response from server", Toast.LENGTH_SHORT).show();
            }
        }, error -> {
            loadingProgressBar.setVisibility(View.INVISIBLE);
            Log.e("UserLogin", "Volley error", error);
            Toast.makeText(UserLogin.this, "Check your Internet Connection", Toast.LENGTH_SHORT).show();
        })
        {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> parmas = new HashMap<>();

                //here we pass params
                parmas.put("crnumber", user1);

                Log.d("UserLogin", "Request Parameters: " + parmas.toString());
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


        if (TextUtils.isEmpty(userid)) {
            usernameEditText.setError("C R Number is required");
            return false;
        }


        return true;

    }
    @Override
    public void onBackPressed() {
        UserLogin.super.onBackPressed();
        Intent intent = new Intent(getApplicationContext(), MainActivity.class);
        startActivity(intent);
        finish();
    }
}