package gmcch.vast.token;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;

import gmcch.vast.token.Doctor.DoctorLogin;
import gmcch.vast.token.ModelClass.DoctorlistModelclass;


public class SharedPrefManager {

    private static final String SHARED_PREF_NAME = "DOCTORDETAILS";
    private static final String DOC_ID = "id";
    private static final String UNIT_ID = "Unitid";
    private static final String DOC_NAME = "docname";

    private static gmcch.vast.token.SharedPrefManager mInstance;
    private Context context;

    public SharedPrefManager(Context context) {
        this.context = context;
    }

    public static synchronized gmcch.vast.token.SharedPrefManager getInstance(Context context){
        if (mInstance==null){
            mInstance=new gmcch.vast.token.SharedPrefManager(context);
        }
        return mInstance;
    }

    public void doctorlogin(DoctorlistModelclass user) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putInt(DOC_ID, user.getId());
        editor.putString(UNIT_ID, user.getUnitid());
        editor.putString(DOC_NAME, user.getName());
        editor.apply();
    }

    //this method will checker whether user is already logged in or not
    public boolean isLoggedIn() {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(DOC_NAME, null) != null;
    }

    //this method will give the logged in user
    public DoctorlistModelclass getdoctor() {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        return new DoctorlistModelclass(
                sharedPreferences.getInt(DOC_ID, -1),
                sharedPreferences.getString(DOC_NAME, null),
                sharedPreferences.getString(UNIT_ID, null)
        );
    }

    //this method will logout the user
    public void logout() {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.clear();
        editor.apply();
        context.startActivity(new Intent(context, DoctorLogin.class));
    }
}
