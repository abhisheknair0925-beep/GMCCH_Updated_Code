package gmcch.vast.token.Adapter;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;


import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import gmcch.vast.token.Config;
import gmcch.vast.token.ModelClass.DoctorUserView;
import gmcch.vast.token.R;


public class DoctorUserViewAdapter extends RecyclerView.Adapter<DoctorUserViewAdapter.ViewHolder> {

    Context context;
    List<DoctorUserView> doctorUserViewArrayList;
    final String text= "Visited";

    public DoctorUserViewAdapter(Context context, List<DoctorUserView> doctorUserViewArrayList) {
        this.context = context;
        this.doctorUserViewArrayList = doctorUserViewArrayList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        return new ViewHolder(LayoutInflater.from(context).inflate(R.layout.doctor_userview_card, parent, false));
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        DoctorUserView item = doctorUserViewArrayList.get(position);
        holder.name.setText(item.getUser_name());
        holder.opno.setText(item.getCr_number());
        holder.tokenno.setText(item.getToken_no());
        holder.viewdetails.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                int currentPosition = holder.getBindingAdapterPosition();
                if (currentPosition != RecyclerView.NO_POSITION) {
                    DoctorUserView currentItem = doctorUserViewArrayList.get(currentPosition);
                    String Cr_number = currentItem.getCr_number();
                    String token = currentItem.getToken_no();
                    setviewed(Cr_number, token);
                    holder.viewdetails.setText(text);
                }
            }
        });
    }

    private void setviewed(String cr_number, String token) {

        //doctorUserViewArrayList.clear();
        RequestQueue requestQueue= Volley.newRequestQueue(context);
        StringRequest stringRequest=new StringRequest(Request.Method.POST, Config.changestatus, response -> {

            Log.d("Response", response);

            String error = "";
            if (response != null) {

                try {
                    JSONObject jsonObject = new JSONObject(response);
                    error = jsonObject.getString("error");
                    if (error.contains("true")) {
                        Toast.makeText(context, jsonObject.getString("message"), Toast.LENGTH_SHORT).show();
                    } else if (error.contains("false")) {
                        Log.v("Error>>>111", "TRUE");
                        String id=jsonObject.getString("message");

                        Toast.makeText(context, id, Toast.LENGTH_SHORT).show();
                 }
                } catch (Exception e) {
                    Log.e("Error>>>111", error);

                    e.printStackTrace();
                }
            }
        }, error -> {
            Log.e("Error", error.toString());
            Toast.makeText(context, error.toString(), Toast.LENGTH_SHORT).show();
        })
        {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> parmas = new HashMap<>();

                //here we pass params
                parmas.put("Cr_number", cr_number);
                parmas.put("token", token);


                return parmas;
            }
        };
        requestQueue.add(stringRequest);
    }

    @Override
    public int getItemCount() {
        return doctorUserViewArrayList.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {

        ImageView imageView;
        TextView name, opno, tokenno;
       final Button viewdetails;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);

            name = itemView.findViewById(R.id.duc_name);
            opno = itemView.findViewById(R.id.duc_age);
            tokenno = itemView.findViewById(R.id.duc_time);
            viewdetails = itemView.findViewById(R.id.duc_view);
        }
    }
}
