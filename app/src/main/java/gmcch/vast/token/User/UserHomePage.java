package gmcch.vast.token.User;

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
import androidx.appcompat.app.AlertDialog;
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
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import gmcch.vast.token.Adapter.CustomDoctorListAdapter;
import gmcch.vast.token.MainActivity;
import gmcch.vast.token.ModelClass.DoctorlistModelclass;
import gmcch.vast.token.ModelClass.UnitModelclass;
import gmcch.vast.token.ModelClass.Users;
import gmcch.vast.token.R;
import gmcch.vast.token.UserSharedPrefManager;


public class UserHomePage extends AppCompatActivity {


    RecyclerView auhp_list1, auhp_list2,auhp_list3;
    //    CustomExpandableListAdapter expandableListAdapter;
    List<UnitModelclass> expandableListTitle = new ArrayList<>();
    UnitModelclass unitModelclass;


    RecyclerView recyclerView;
    ProgressBar simpleProgressBar, simpleProgressBar1;

    String id, name;
    Calendar calendar;
    int day;
    String todayday;
    Toolbar toolbar;

    TextView username, auhp_booktocken1, auhp_booktocken2,auhp_booktocken3;

    List<DoctorlistModelclass> doctorlistModelclassList1 = new ArrayList<>();
    List<DoctorlistModelclass> doctorlistModelclassList2 = new ArrayList<>();
    List<DoctorlistModelclass> doctorlistModelclassList3 = new ArrayList<>();
    String passingday, type;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_home_page);

        Users users = UserSharedPrefManager.getInstance1(this).getUser();
        id = users.getCR_number();
        name = users.getName();

        toolbar = findViewById(R.id.toolbarlayout1);
        setSupportActionBar(toolbar);

        ActionBar actionBar = getSupportActionBar();
        assert actionBar != null;
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setDisplayShowCustomEnabled(true);

        calendar = Calendar.getInstance();
        day = calendar.get(Calendar.DAY_OF_WEEK);

        switch (day) {
            case Calendar.SUNDAY:
                // Current day is Sunday
                todayday = "SUNDAY";
                break;
            case Calendar.MONDAY:
                // Current day is Monday
                todayday = "MONDAY";
                break;
            case Calendar.TUESDAY:
                // etc.
                todayday = "TUESDAY";
                break;
            case Calendar.WEDNESDAY:
                // etc.
                todayday = "WEDNESDAY";
                break;
            case Calendar.THURSDAY:
                // etc.
                todayday = "THURSDAY";
                break;
            case Calendar.FRIDAY:
                // etc.
                todayday = "FRIDAY";
                break;
            case Calendar.SATURDAY:
                // etc.
                todayday = "SATURDAY";
                break;
        }
        auhp_booktocken1 = findViewById(R.id.auhp_booktocken1);
        auhp_booktocken2 = findViewById(R.id.auhp_booktocken2);
        auhp_booktocken3 = findViewById(R.id.auhp_booktocken3);
        auhp_list1 = findViewById(R.id.auhp_list1);
        auhp_list2 = findViewById(R.id.auhp_list2);
        auhp_list3 = findViewById(R.id.auhp_list3);
//        expandableListView = findViewById(R.id.auhp_list1);
        simpleProgressBar = findViewById(R.id.auhp_simpleProgressBar);
        username = findViewById(R.id.appbar_username);

        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(this);
        auhp_list1.setLayoutManager(linearLayoutManager);

        LinearLayoutManager linearLayoutManager1 = new LinearLayoutManager(this);
        auhp_list2.setLayoutManager(linearLayoutManager1);

        LinearLayoutManager linearLayoutManager3 = new LinearLayoutManager(this);
        auhp_list3.setLayoutManager(linearLayoutManager3);


        username.setText(name);
//        getunitdetails();

//        Toast.makeText(this, id +" "+name, Toast.LENGTH_SHORT).show();

        getDocdetails("1", doctorlistModelclassList1, auhp_list1);
        getDocdetails("2", doctorlistModelclassList2, auhp_list2);
        getDocdetails("3", doctorlistModelclassList3, auhp_list3);
        auhp_booktocken1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                booktockens("1");
            }
        });

        auhp_booktocken2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                booktockens("2");
            }
        });

        auhp_booktocken3.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                booktockens("3");
            }
        });

    }



    private void booktockens(String unitid) {
        switch (todayday) {
            case "SUNDAY":
                passingday = "MONDAY";
                break;
            case "MONDAY":
                passingday = "TUESDAY";
                break;
            case "TUESDAY":
                passingday = "WEDNESDAY";
                break;
            case "WEDNESDAY":
                passingday = "THURSDAY";
                break;
            case "THURSDAY":
                passingday = "FRIDAY";
                break;
            case "FRIDAY":
                passingday = "SATURDAY";
                break;
            case "SATURDAY":
                passingday = "SUNDAY";
                break;
        }

        AlertDialog.Builder alertbox = new AlertDialog.Builder(UserHomePage.this);
        alertbox.setTitle("Book Token");
        alertbox.setMessage("Select your booking type for Op:"+unitid+" ");
        alertbox.setNegativeButton("Chemotherapy", (dialog, which) -> {
            type = "chemo";
            if (passingday.equals("SUNDAY")) {
                Toast.makeText(getApplicationContext(), "You cannot take token today", Toast.LENGTH_SHORT).show();
            } else if (unitid.equals("1") && passingday.equals("MONDAY")) {
                booktoken(unitid, type);
            } else if (unitid.equals("3") && passingday.equals("THURSDAY")) {
                booktoken(unitid, type);
            } else if (unitid.equals("2") && passingday.equals("TUESDAY")) {
                booktoken(unitid, type);
            } else if (unitid.equals("2") && passingday.equals("FRIDAY")) {
                booktoken(unitid, type);
            } else if (unitid.equals("1") && passingday.equals("WEDNESDAY")) {
                booktoken(unitid, type);
            } else if (unitid.equals("3") && passingday.equals("SATURDAY")) {
                booktoken(unitid, type);
            } else
                Toast.makeText(getApplicationContext(), "You cannot book token for this OP today !!!!", Toast.LENGTH_SHORT).show();
        });
        alertbox.setPositiveButton("Follow-up", (arg0, arg1) -> {
            type = "normal";
            if (passingday.equals("SUNDAY")) {
                Toast.makeText(getApplicationContext(), "You cannot take token today", Toast.LENGTH_SHORT).show();
            } else if (unitid.equals("1") && passingday.equals("MONDAY")) {
                booktoken(unitid, type);
            } else if (unitid.equals("3") && passingday.equals("THURSDAY")) {
                booktoken(unitid, type);
            } else if (unitid.equals("2") && passingday.equals("TUESDAY")) {
                booktoken(unitid, type);
            } else if (unitid.equals("2") && passingday.equals("FRIDAY")) {
                booktoken(unitid, type);
            } else if (unitid.equals("1") && passingday.equals("WEDNESDAY")) {
                booktoken(unitid, type);
            } else if (unitid.equals("3") && passingday.equals("SATURDAY")) {
                booktoken(unitid, type);
            } else
                Toast.makeText(getApplicationContext(), "You cannot book token for this OP today !!!!", Toast.LENGTH_SHORT).show();
        });
        alertbox.show();
    }

    private void booktoken(String unitid, String type) {
        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());
        StringRequest stringRequest = new StringRequest(Request.Method.POST, gmcch.vast.token.Config.Booking,
                response -> {
                    Log.v("HELLOpoiuyt", response);
                    if (response != null) {
                        try {
                            JSONObject jobject = new JSONObject(response);

                            String error = jobject.getString("error");
                            String message = jobject.getString("message");

                            Toast.makeText(getApplicationContext(), message, Toast.LENGTH_SHORT).show();
                            Intent intent = new Intent(getApplicationContext(), ViewCurentBooking.class);
                            startActivity(intent);

                        } catch (JSONException e) {
                            e.printStackTrace();
                            Toast.makeText(getApplicationContext(), "No Data", Toast.LENGTH_SHORT).show();
                        }
                    }

                },
                error -> {
                    Toast.makeText(getApplicationContext(), "Error response", Toast.LENGTH_SHORT).show();
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> parmas = new HashMap<>();
                parmas.put("Cr_number", id);
                parmas.put("User_name", name);
                parmas.put("Unit_id", unitid);
                parmas.put("Type", type);
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
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.profile_menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if (id == R.id.menu_logout) {
            UserSharedPrefManager.getInstance1(getApplicationContext()).logout();
            return true;
        } else if (id == R.id.menu_viewmybooking) {
            Intent intent = new Intent(getApplicationContext(), ViewCurentBooking.class);
            startActivity(intent);
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    private void getDocdetails(String unitId, List<DoctorlistModelclass> list, RecyclerView recyclerView) {
        simpleProgressBar.setVisibility(View.VISIBLE);
        list.clear();

        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());

        StringRequest stringRequest = new StringRequest(Request.Method.POST, gmcch.vast.token.Config.User_doctor_view,
                response -> {
                    simpleProgressBar.setVisibility(View.INVISIBLE);
                    Log.d("UserHomePage", "Response for unit " + unitId + ": " + response);

                    if (response != null && !response.isEmpty()) {
                        try {
                            JSONObject jobject = new JSONObject(response);
                            if (jobject.has("doctdetails")) {
                                JSONArray jsonArray = jobject.getJSONArray("doctdetails");

                                for (int i = 0; i < jsonArray.length(); i++) {
                                    DoctorlistModelclass doctor = new DoctorlistModelclass();
                                    JSONObject jobject1 = jsonArray.getJSONObject(i);

                                    doctor.setId(jobject1.getInt("Id"));
                                    doctor.setName(jobject1.getString("Dr_name"));
                                    doctor.setQualification(jobject1.getString("Dr_qualification"));
                                    doctor.setDepartment(jobject1.getString("discription"));
                                    doctor.setImage(jobject1.getString("image"));
                                    list.add(doctor);
                                }
                            } else if (jobject.has("message")) {
                                Toast.makeText(getApplicationContext(), jobject.getString("message"), Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(getApplicationContext(), "No Data for Unit " + unitId, Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            Log.e("UserHomePage", "JSON Parsing error for unit " + unitId, e);
                            Toast.makeText(getApplicationContext(), "Data format error for Unit " + unitId, Toast.LENGTH_SHORT).show();
                        }
                    } else {
                        Toast.makeText(getApplicationContext(), "Empty response for Unit " + unitId, Toast.LENGTH_SHORT).show();
                    }
                    CustomDoctorListAdapter adapter = new CustomDoctorListAdapter(UserHomePage.this, list);
                    recyclerView.setAdapter(adapter);
                },
                error -> {
                    simpleProgressBar.setVisibility(View.INVISIBLE);
                    Log.e("UserHomePage", "Volley error for unit " + unitId, error);
                    Toast.makeText(getApplicationContext(), "Network Error for Unit " + unitId, Toast.LENGTH_SHORT).show();
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> parmas = new HashMap<>();
                parmas.put("unit_id", unitId);
                return parmas;
            }
        };
        stringRequest.setRetryPolicy(new DefaultRetryPolicy(
                15000,
                0, // No retries to avoid duplicate requests if it's a timeout issue
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(stringRequest);
    }


//    private void getunitdetails() {
//        expandableListTitle.clear();
//        simpleProgressBar.setVisibility(View.VISIBLE);
//
//        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());
//
//        StringRequest stringRequest = new StringRequest(Request.Method.GET, Config.view_units,
//                response -> {
//                    simpleProgressBar.setVisibility(View.INVISIBLE);
//
//                    Log.v("HELLO", response);
//
//                    if (response != null) {
//
//                        try {
//                            JSONObject jobject = new JSONObject(response);
//                            JSONArray jsonArray = jobject.getJSONArray("unitdetails");
//
//                            for (int i = 0; i < jsonArray.length(); i++) {
//                                unitModelclass = new UnitModelclass();
//                                JSONObject jobject1 = jsonArray.getJSONObject(i);
//
//                                String ID = jobject1.getString("Id");
//                                String name = jobject1.getString("Unit_name");
//                                String time = jobject1.getString("Unit_time");
//
//                                unitModelclass.setUnitid(ID);
//                                unitModelclass.setUnitname(name);
//                                unitModelclass.setTime(time);
//
//                                expandableListTitle.add(unitModelclass);
//                            }
//
//                        } catch (JSONException e) {
//                            e.printStackTrace();
//                            Toast.makeText(getApplicationContext(), "No Data", Toast.LENGTH_SHORT).show();
//                        }
//                    }
//                    expandableListAdapter = new CustomExpandableListAdapter(getApplicationContext(), expandableListTitle, todayday);
//                    expandableListView.setAdapter(expandableListAdapter);
//                },
//                error -> {
//                    Toast.makeText(getApplicationContext(), "Error response", Toast.LENGTH_SHORT).show();
////                        Toast.makeText(getContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
//                });
//        queue.add(stringRequest);
//
//    }

    @Override
    public void onBackPressed() {
        UserHomePage.super.onBackPressed();
        Intent intent = new Intent(getApplicationContext(), MainActivity.class);
        startActivity(intent);
        finish();
    }
}