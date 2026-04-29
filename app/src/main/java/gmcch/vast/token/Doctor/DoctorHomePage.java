package gmcch.vast.token.Doctor;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;


import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import gmcch.vast.token.Adapter.DoctorUserViewAdapter;
import gmcch.vast.token.MainActivity;
import gmcch.vast.token.ModelClass.DoctorUserView;
import gmcch.vast.token.ModelClass.DoctorlistModelclass;
import gmcch.vast.token.R;


public class DoctorHomePage extends AppCompatActivity {

    RecyclerView recyclerView;
    List<DoctorUserView> list = new ArrayList<>();
    DoctorUserView doctorUserView ;
    DoctorUserViewAdapter doctorUserViewAdapter;
    String id, docname, unitid;
    Toolbar toolbar;
    TextView displayusername;
    TextView noofbookings;

    int id1;
    ProgressBar simpleProgressBar;

    TextView username;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_doctor_home_page);

        recyclerView = findViewById(R.id.adhp_recyclerview);
        simpleProgressBar=findViewById(R.id.adhp__simpleProgressBar);
        noofbookings = findViewById(R.id.adhp_noofbookings);
        username = findViewById(R.id.appbar_name);

        DoctorlistModelclass user = gmcch.vast.token.SharedPrefManager.getInstance(this).getdoctor();
        id1 = user.getId();
        unitid = user.getUnitid();
        docname=user.getName();


        id= String.valueOf(id1);

        username.setText(docname);

        toolbar=findViewById(R.id.toolbarlayout);
        setSupportActionBar(toolbar);




        ActionBar actionBar = getSupportActionBar();
        assert actionBar != null;
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setDisplayShowCustomEnabled(true);

        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(this);
        recyclerView.setLayoutManager(linearLayoutManager);


        viewbokings();
    }
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.profile_menu1, menu);
        return true;
    }
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if (id == R.id.menu_logout) {
            gmcch.vast.token.SharedPrefManager.getInstance(getApplicationContext()).logout();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    private void viewbokings() {

        simpleProgressBar.setVisibility(View.VISIBLE);

        list.clear();
        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());

//        doctorUserViewAdapter.notifyDataSetChanged();
        StringRequest stringRequest = new StringRequest(Request.Method.POST, gmcch.vast.token.Config.doctor_user_view_bookings,
                response -> {
                    simpleProgressBar.setVisibility(View.INVISIBLE);

                    Log.d("DoctorHomePage", "Response: " + response);

                    if (response != null && !response.isEmpty()) {
                        try {
                            JSONObject jobject = new JSONObject(response);

                            if (jobject.has("No_of_bookings")) {
                                noofbookings.setText("You have " + jobject.getString("No_of_bookings") + " Bookings");
                            }

                            if (jobject.has("details")) {
                                JSONArray jsonArray = jobject.getJSONArray("details");

                                for (int i = 0; i < jsonArray.length(); i++) {
                                    doctorUserView = new DoctorUserView();
                                    JSONObject jobject1 = jsonArray.getJSONObject(i);

                                    doctorUserView.setId(jobject1.getString("Id"));
                                    doctorUserView.setCr_number(jobject1.getString("Cr_number"));
                                    doctorUserView.setUser_name(jobject1.getString("User_name"));
                                    doctorUserView.setUnit_name(jobject1.getString("Unit_name"));
                                    doctorUserView.setToken_no(jobject1.getString("token_no"));
                                    doctorUserView.setBooking_status(jobject1.getString("Booking_status"));

                                    list.add(doctorUserView);
                                }
                            } else if (jobject.has("message")) {
                                Toast.makeText(getApplicationContext(), jobject.getString("message"), Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            Log.e("DoctorHomePage", "JSON Parsing error", e);
                            Toast.makeText(getApplicationContext(), "You don't have any booking today", Toast.LENGTH_SHORT).show();
                        }
                    } else {
                        Toast.makeText(getApplicationContext(), "Empty response from server", Toast.LENGTH_SHORT).show();
                    }
                    doctorUserViewAdapter = new DoctorUserViewAdapter(DoctorHomePage.this, list);
                    recyclerView.setAdapter(doctorUserViewAdapter);
                    doctorUserViewAdapter.notifyDataSetChanged();
                },
                error -> {
                    simpleProgressBar.setVisibility(View.INVISIBLE);
                    Log.e("DoctorHomePage", "Volley error", error);
                    Toast.makeText(getApplicationContext(), "Error response", Toast.LENGTH_SHORT).show();
                })

        {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> parmas = new HashMap<>();

                //here we pass params
                parmas.put("unitid", unitid);

                return parmas;
            }
        };
        stringRequest.setRetryPolicy(new DefaultRetryPolicy(
                15000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(stringRequest);

    }
    @Override
    public void onBackPressed() {
        DoctorHomePage.super.onBackPressed();
        Intent intent=new Intent(getApplicationContext(), MainActivity.class);
        startActivity(intent);
    }
}