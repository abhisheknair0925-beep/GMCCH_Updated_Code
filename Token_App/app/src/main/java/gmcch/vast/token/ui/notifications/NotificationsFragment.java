package gmcch.vast.token.ui.notifications;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

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
import gmcch.vast.token.MainActivity;
import gmcch.vast.token.ModelClass.MyBookingModelClass;
import gmcch.vast.token.ModelClass.Users;
import gmcch.vast.token.R;
import gmcch.vast.token.SplashScreen;
import gmcch.vast.token.User.UserLogin;
import gmcch.vast.token.UserSharedPrefManager;

public class NotificationsFragment extends Fragment {

    MyBookingModelClass myBookingModelClass;
    List<MyBookingModelClass> myBookingModelClassArrayList = new ArrayList<>();
    MybookingviewAdapter mybookingviewAdapter;
    ProgressBar simpleProgressBar, simpleProgressBar1;
    String id, name;
    RecyclerView recyclerView;

    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {


        View root = inflater.inflate(R.layout.fragment_notifications, container, false);
        Users users = UserSharedPrefManager.getInstance1(getActivity()).getUser();
        id = users.getCR_number();
        name = users.getName();

        recyclerView = root.findViewById(R.id.brecycler);
        simpleProgressBar1 = root.findViewById(R.id.bprogressbar);

        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getActivity());
        recyclerView.setLayoutManager(linearLayoutManager);
checklogin();

        return root;
    }

    private void checklogin() {
        if(new UserSharedPrefManager(getActivity()).isLoggedIn()){
            getmybookingdetails();
        }
        else{
            Toast.makeText(getActivity(), "Please login to view bookings", Toast.LENGTH_SHORT).show();
            AlertDialog.Builder alertbox = new AlertDialog.Builder(getActivity());
            alertbox.setTitle("Please login");
            alertbox.setNegativeButton("login", (dialog, which) -> {
                Intent intent = new Intent(getActivity(), UserLogin.class);
                startActivity(intent);
                getActivity().finish();
            });
            alertbox.show();

        }
    }

    private void getmybookingdetails() {
        myBookingModelClassArrayList.clear();
        simpleProgressBar1.setVisibility(View.VISIBLE);

        RequestQueue queue = Volley.newRequestQueue(getActivity());

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
                            Toast.makeText(getActivity(), "No Data", Toast.LENGTH_SHORT).show();
                        }
                    }
                    mybookingviewAdapter = new MybookingviewAdapter(getActivity(), myBookingModelClassArrayList);
                    recyclerView.setAdapter(mybookingviewAdapter);
                },
                error -> {
                    Toast.makeText(getActivity(), "Error response", Toast.LENGTH_SHORT).show();
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

        queue.add(stringRequest);

    }
    }
