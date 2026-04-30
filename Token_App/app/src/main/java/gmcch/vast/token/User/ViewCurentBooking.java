package gmcch.vast.token.User;

import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
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

import gmcch.vast.token.Adapter.MybookingviewAdapter;
import gmcch.vast.token.Config;
import gmcch.vast.token.ModelClass.MyBookingModelClass;
import gmcch.vast.token.ModelClass.Users;
import gmcch.vast.token.R;
import gmcch.vast.token.UserSharedPrefManager;


public class ViewCurentBooking extends AppCompatActivity {
    MyBookingModelClass myBookingModelClass;
    List<MyBookingModelClass> myBookingModelClassArrayList = new ArrayList<>();
    MybookingviewAdapter mybookingviewAdapter;
    ProgressBar simpleProgressBar, simpleProgressBar1;
    String id, name;
    RecyclerView recyclerView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_view_curent_booking);

        Users users = UserSharedPrefManager.getInstance1(this).getUser();
        id = users.getCR_number();
        name = users.getName();

        recyclerView = findViewById(R.id.auhp_recycler);
        simpleProgressBar1 = findViewById(R.id.auhp_simpleProgressBar1);

        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(this);
        recyclerView.setLayoutManager(linearLayoutManager);

        getmybookingdetails();
    }
    private void getmybookingdetails() {
        myBookingModelClassArrayList.clear();
        simpleProgressBar1.setVisibility(View.VISIBLE);

        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());

        StringRequest stringRequest = new StringRequest(Request.Method.POST, Config.Your_current_booking,
                response -> {
                    simpleProgressBar1.setVisibility(View.INVISIBLE);

                    Log.v("HELLOBDSKJFBASK", response);

                    if (response != null) {

                        try {
                            JSONObject jobject = new JSONObject(response);
                            JSONArray jsonArray = jobject.getJSONArray("mybookingdetails");

                            for (int i = 0; i< jsonArray.length(); i++) {
                                myBookingModelClass = new MyBookingModelClass();
                                JSONObject jobject1 = jsonArray.getJSONObject(i);

                                String ID = jobject1.getString("Id");
                                String name = jobject1.getString("Unit_id");
                                String date = jobject1.getString("Date");
                                String token = jobject1.getString("Token");
                                String status = jobject1.getString("Booking_status");
                                String Cr_number = jobject1.getString("Cr_number");

                                myBookingModelClass.setId(ID);
                                myBookingModelClass.setUnit_id(name);
                                myBookingModelClass.setDate(date);
                                myBookingModelClass.setToken(token);
                                myBookingModelClass.setBookingstatus(status);
                                myBookingModelClass.setCrno(Cr_number);

                                myBookingModelClassArrayList.add(myBookingModelClass);
                            }

                        } catch (JSONException e) {
                            e.printStackTrace();
                            Toast.makeText(getApplicationContext(), "No Data", Toast.LENGTH_SHORT).show();
                        }
                    }
                    mybookingviewAdapter = new MybookingviewAdapter(ViewCurentBooking.this, myBookingModelClassArrayList);
                    recyclerView.setAdapter(mybookingviewAdapter);
                },
                error -> {
                    Toast.makeText(getApplicationContext(), "Error response", Toast.LENGTH_SHORT).show();
//                        Toast.makeText(getContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> parmas = new HashMap<>();

                //here we pass params
                parmas.put("Cr_number", id);


                return parmas;
            }
        };
        stringRequest.setRetryPolicy(new DefaultRetryPolicy(
                15000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        queue.add(stringRequest);

    }

}