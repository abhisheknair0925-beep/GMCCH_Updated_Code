package gmcch.vast.token.ModelClass;

public class MyBookingModelClass {

    String Id, Unit_id, Date, Token, bookingstatus, crno;

    public MyBookingModelClass() {

    }

    public String getCrno() {
        return crno;
    }

    public void setCrno(String crno) {
        this.crno = crno;
    }

    public String getId() {
        return Id;
    }

    public void setId(String id) {
        Id = id;
    }

    public String getUnit_id() {
        return Unit_id;
    }

    public void setUnit_id(String unit_id) {
        Unit_id = unit_id;
    }

    public String getDate() {
        return Date;
    }

    public void setDate(String date) {
        Date = date;
    }

    public String getToken() {
        return Token;
    }

    public void setToken(String token) {
        Token = token;
    }

    public String getBookingstatus() {
        return bookingstatus;
    }

    public void setBookingstatus(String bookingstatus) {
        this.bookingstatus = bookingstatus;
    }
}
