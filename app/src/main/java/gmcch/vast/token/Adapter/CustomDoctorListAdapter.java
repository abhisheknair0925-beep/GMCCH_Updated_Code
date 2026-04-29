package gmcch.vast.token.Adapter;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.squareup.picasso.Picasso;

import java.util.List;

import gmcch.vast.token.Config;
import gmcch.vast.token.ModelClass.DoctorlistModelclass;
import gmcch.vast.token.R;


public class CustomDoctorListAdapter  extends RecyclerView.Adapter<CustomDoctorListAdapter.MyViewHolder> {

    Context context;
    List<DoctorlistModelclass> doctorlistModelclassList;

    public CustomDoctorListAdapter(Context context, List<DoctorlistModelclass> doctorlistModelclassList) {
        this.context = context;
        this.doctorlistModelclassList = doctorlistModelclassList;
    }


    @NonNull
    @Override
    public MyViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.user_doctordetails_card, null);
        return new MyViewHolder(v);
    }

    @Override
    public void onBindViewHolder(@NonNull MyViewHolder holder, int position) {
        holder.name.setText(doctorlistModelclassList.get(position).getName());
        holder.quali.setText(doctorlistModelclassList.get(position).getQualification());
        String imageurl = doctorlistModelclassList.get(position).getImage();

        if (!imageurl.equals("")){

//            Toast.makeText(context, imageurl, Toast.LENGTH_SHORT).show();
            Picasso.get().load(Config.imgbase_url+imageurl)
                   .fit()
                   .centerCrop()
                    .into(holder.imageView);
            Log.v("IMAGEURL",Config.imgbase_url+imageurl);
        }
    }

    @Override
    public int getItemCount() {
        return doctorlistModelclassList.size();
    }

    public static class MyViewHolder extends RecyclerView.ViewHolder {

        TextView name, quali, desp;
        ImageView imageView;
        public MyViewHolder(@NonNull View itemView) {
            super(itemView);

            name=itemView.findViewById(R.id.uddc_name);
            quali=itemView.findViewById(R.id.uddc_desg);
            imageView=itemView.findViewById(R.id.uddc_profileimg);
        }
    }
}
