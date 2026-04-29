package gmcch.vast.token;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;

import gmcch.vast.token.ModelClass.Users;
import gmcch.vast.token.User.UserLogin;


public class UserSharedPrefManager {

    private static final String SHARED_PREF_NAME = "USERDETAILS";
    private static final String USER_ID = "id";
    private static final String USER_NAME = "username";
    private static final String USER_CR_NO = "crno";

    private static gmcch.vast.token.UserSharedPrefManager mInstance;
    private Context context;

    public UserSharedPrefManager(Context context) {
        this.context = context;
    }

    public static synchronized gmcch.vast.token.UserSharedPrefManager getInstance1(Context context){
        if (mInstance==null){
            mInstance=new gmcch.vast.token.UserSharedPrefManager(context);
        }
        return mInstance;
    }

    public void userLogin(Users user) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putInt(USER_ID, user.getId());
        editor.putString(USER_NAME, user.getName());
        editor.putString(USER_CR_NO, user.getCR_number());
        editor.apply();
    }

    //this method will checker whether user is already logged in or not
    public boolean isLoggedIn() {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(USER_CR_NO, null) != null;
    }

    //this method will give the logged in user
    public Users getUser() {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        return new Users(
                sharedPreferences.getInt(USER_ID, -1),
                sharedPreferences.getString(USER_NAME, null),
                sharedPreferences.getString(USER_CR_NO, null)
        );
    }

    //this method will logout the user
    public void logout() {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.clear();
        editor.apply();
        context.startActivity(new Intent(context, UserLogin.class));
    }
}
