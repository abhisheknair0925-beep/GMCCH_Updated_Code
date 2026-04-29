package gmcch.vast.token.ModelClass;

public class DoctorUserView {
    String Id,Cr_number, User_name, Unit_name, token_no, Booking_status;

    public DoctorUserView() {

    }

    public String getId() {
        return Id;
    }

    public void setId(String id) {
        Id = id;
    }

    public String getCr_number() {
        return Cr_number;
    }

    public void setCr_number(String cr_number) {
        Cr_number = cr_number;
    }

    public String getUser_name() {
        return User_name;
    }

    public void setUser_name(String user_name) {
        User_name = user_name;
    }

    public String getUnit_name() {
        return Unit_name;
    }

    public void setUnit_name(String unit_name) {
        Unit_name = unit_name;
    }

    public String getToken_no() {
        return token_no;
    }

    public void setToken_no(String token_no) {
        this.token_no = token_no;
    }

    public String getBooking_status() {
        return Booking_status;
    }

    public void setBooking_status(String booking_status) {
        Booking_status = booking_status;
    }
}
