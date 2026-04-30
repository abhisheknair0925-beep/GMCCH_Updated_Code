package gmcch.vast.token.ui.home;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;

import gmcch.vast.token.Doctor.DoctorLogin;
import gmcch.vast.token.R;
import gmcch.vast.token.User.UserLogin;

public class HomeFragment extends Fragment {

    Button buttonPatient, buttonDoctor;


    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {
        View root = inflater.inflate(R.layout.fragment_home, container, false);
        buttonPatient = root.findViewById(R.id.button_patient);
        buttonDoctor = root.findViewById(R.id.button_doctor);


        buttonDoctor.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getContext(), DoctorLogin.class);
                startActivity(intent);
            }
        });
        buttonPatient.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getContext(), UserLogin.class);
                startActivity(intent);
            }
        });

        return root;
    }




}