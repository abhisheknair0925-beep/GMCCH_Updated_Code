package gmcch.vast.token.ModelClass;

public class Users {


    private int id;
    private String Name;
    private String CR_number;
    private String Age;
    private String Gender;

    public Users() {
    }

    public Users(int id, String name, String CR_number) {
        this.id = id;
        this.Name = name;
        this.CR_number = CR_number;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return Name;
    }

    public void setName(String name) {
        Name = name;
    }

    public String getCR_number() {

        return CR_number;
    }

    public void setCR_number(String CR_number) {
        this.CR_number = CR_number;
    }

    public String getAge() {
        return Age;
    }

    public void setAge(String age) {
        Age = age;
    }

    public String getGender() {
        return Gender;
    }

    public void setGender(String gender) {
        Gender = gender;
    }
}
