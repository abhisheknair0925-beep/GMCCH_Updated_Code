package gmcch.vast.token.ModelClass;

public class DoctorlistModelclass {
    private String Name,Qualification, Unitid, Phone, Gender,Department,regno, image;

    private int id;

    public DoctorlistModelclass(int id, String name, String unitid) {
        this.id = id;
        this.Name = name;
        this.Unitid = unitid;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public DoctorlistModelclass() {
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

    public String getQualification() {
        return Qualification;
    }

    public void setQualification(String qualification) {
        Qualification = qualification;
    }

    public String getUnitid() {
        return Unitid;
    }

    public void setUnitid(String unitid) {
        Unitid = unitid;
    }

    public String getPhone() {
        return Phone;
    }

    public void setPhone(String phone) {
        Phone = phone;
    }

    public String getGender() {
        return Gender;
    }

    public void setGender(String gender) {
        Gender = gender;
    }

    public String getDepartment() {
        return Department;
    }

    public void setDepartment(String department) {
        Department = department;
    }

    public String getRegno() {
        return regno;
    }

    public void setRegno(String regno) {
        this.regno = regno;
    }
}
