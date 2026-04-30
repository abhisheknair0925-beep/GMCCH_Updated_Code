package gmcch.vast.token.Adapter;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.recyclerview.widget.RecyclerView;


import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import gmcch.vast.token.ModelClass.MyBookingModelClass;
import gmcch.vast.token.R;
import gmcch.vast.token.User.UserHomePage;


public class MybookingviewAdapter extends RecyclerView.Adapter<MybookingviewAdapter.MyViewHolder> {

    Context context;
    List<MyBookingModelClass> myBookingModelClassList;
    String Expectedtime;

    public MybookingviewAdapter(Context context, List<MyBookingModelClass> myBookingModelClassList) {
        this.context = context;
        this.myBookingModelClassList = myBookingModelClassList;
    }

    @NonNull
    @Override
    public MyViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        return new MyViewHolder(LayoutInflater.from(context).inflate(R.layout.userview_doctor_card, parent, false));
    }

    @Override
    public void onBindViewHolder(@NonNull MyViewHolder holder, int position) {
        MyBookingModelClass item = myBookingModelClassList.get(position);
        holder.name.setText("Booking Status : "+item.getBookingstatus());
        holder.date.setText("Date : "+item.getDate());
        int token = Integer.parseInt(item.getToken());
        if (token<=50){
            Expectedtime="8AM";
        }
        else if (token>50&&token<=100){
            Expectedtime="8:30AM";
        }
        else if (token>100&&token<=150){
            Expectedtime="9AM";
        }
        else if (token>150&&token<=200){
            Expectedtime="9:30AM";
        }
        else if (token>200&&token<=250){
            Expectedtime="10AM";
        }
        else if (token>250&&token<=300){
            Expectedtime="10:30AM";
        }
        else if (token>300&&token<=350){
            Expectedtime="11AM";
        }
        else if (token>350&&token<=400){
            Expectedtime="11:30AM";
        }
        else if (token>400&&token<=450){
            Expectedtime="12PM";
        }
        else if (token>450&&token<=500){
            Expectedtime="12:30PM";
        }

        holder.exptime.setText("Expected time : "+Expectedtime);
        holder.tokenno.setText("Token No : "+item.getToken());
        if (!(item.getBookingstatus()).equals("Visited")&&!(item.getBookingstatus()).equals("Approved")){
            holder.cancel.setVisibility(View.VISIBLE);
            holder.cancel.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    int currentPosition = holder.getBindingAdapterPosition();
                    if (currentPosition != RecyclerView.NO_POSITION) {
                        MyBookingModelClass currentItem = myBookingModelClassList.get(currentPosition);
                        String crno = currentItem.getCrno();
                        String token = currentItem.getToken();
                        String date = currentItem.getDate();
                        AlertDialog.Builder alertbox =  new AlertDialog.Builder(view.getRootView().getContext());
                        alertbox.setTitle("Cancel Token");
                        alertbox   .setMessage("Are you sure you want to cancel token ?");
                        alertbox   .setNegativeButton(android.R.string.no, null);
                        alertbox    .setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {

                            public void onClick(DialogInterface arg0, int arg1) {
                                cancelbooking(crno, token, date);
                            }
                        });
                        alertbox.show();
                    }
                }
            });
        }
    }

    private void cancelbooking(String crno, String token, String date) {
        RequestQueue queue = Volley.newRequestQueue(context);

        StringRequest stringRequest = new StringRequest(Request.Method.POST, gmcch.vast.token.Config.cancel,
                response -> {

                    Log.v("HELLOpoiuyt",response);

                    if(response!=null)
                    {

                        try {
                            JSONObject jobject=new JSONObject(response);

                            String error = jobject.getString("error");
                            String message = jobject.getString("message");

                            Toast.makeText(context, message, Toast.LENGTH_SHORT).show();
                            Intent intent = new Intent(context, UserHomePage.class);
                            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                            context.startActivity(intent);

                        } catch (JSONException e) {
                            e.printStackTrace();
                            Toast.makeText(context, "No Data", Toast.LENGTH_SHORT).show();
                        }
                    }

                },
                error -> {
                    Toast.makeText(context, "Error response", Toast.LENGTH_SHORT).show();
//                        Toast.makeText(getContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
                })
        {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> parmas = new HashMap<>();

                //here we pass params
                parmas.put("crnumber", crno);
                parmas.put("token", token);
                parmas.put("date", date);


                return parmas;
            }
        };
        queue.add(stringRequest);

    }

    @Override
    public int getItemCount() {
        return myBookingModelClassList.size();
    }

    public static class MyViewHolder extends RecyclerView.ViewHolder {

        TextView date, name, tokenno, exptime;
        Button cancel;
        public MyViewHolder(@NonNull View itemView) {
            super(itemView);

            exptime=itemView.findViewById(R.id.uvdc_tokentime);
            date=itemView.findViewById(R.id.uvdc_date);
            name= itemView.findViewById(R.id.uvdc_name);
            tokenno = itemView.findViewById(R.id.uvdc_tokenno);
            cancel = itemView.findViewById(R.id.uvdc_cancelbooking);
        }
    }
}
