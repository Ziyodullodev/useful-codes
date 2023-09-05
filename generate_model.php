<?php

// generate model class

class Model
{
    public $directory = "models";
    private $require_base = 'require_once "Base_model.php";';
    private $extend_base = 'extends Base_model';
    private $varible_head = "$";
    private $end_varible = ";";
    private $varible_value = "=";
    private $sql_select = 'ses';
    private $types = [
        "public",
        "protected",
        "private"
    ];
    private $tab = "    ";
    private $probel = " ";
    private $new_line = "\n";
    private $class_name = "class";
    private $function_name = "function";
    private $close_php = "?>";
    private $open_php = "<?php";
    private $close_class = "}";
    private $open_class = "{";
    private $get_function = '
    public function get() {
          $stmt = $this->db->query("SELECT * FROM $this->model_name");
          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $data;
     }';

    private $update_function = '
    public function update($data, $id = false)
    {
        if ($id) {
        return "id bolishi kerak";
        } 
        $placeholders = [];
        foreach ($data as $key => $value) {
            // Escape and sanitize the value
            $escapedValue = $this->db->quote($value);
            $placeholders[] = "$key = $escapedValue";
        }
        
        $updateFields = implode(",", $placeholders);

        $sql = "UPDATE $this->model_name SET $updateFields WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);

        return $stmt->execute();
    }';

    private $delete_function = '
    public function delete($id)
    {
        $sql = "DELETE FROM $this->model_name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }';

    private $insert_function = '
    public function insert($data)
    {
        $placeholders = [];
        foreach ($data as $key => $value) {
            // Escape and sanitize the value
            $escapedValue = $this->db->quote($value);
            $placeholders[] = "$key = $escapedValue";
        }
        
        $updateFields = implode(",", $placeholders);

        $sql = "INSERT INTO $this->model_name SET $updateFields";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute();
    }';

    private $select_function = '
    public function select($id)
    {
        $sql = "SELECT * FROM $this->model_name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }';

    private $create_table = '
    public function create_table($data)
    {
        $placeholders = [];
        foreach ($data as $key => $value) {
            // Escape and sanitize the value
            $escapedValue = $this->db->quote($value);
            $placeholders[] = "$key = $escapedValue";
        }
        
        $updateFields = implode(",", $placeholders);

        $sql = "CREATE TABLE $this->model_name SET $updateFields";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute();
    }';

    public function create_model($classname)
    {

        $compiled =
            // write php open tag
            $this->open_php . $this->new_line . $this->new_line
            // write require base class
            . $this->require_base . $this->new_line . $this->new_line
            // write class name extends base class
            . $this->class_name . " " . $classname . " " . $this->extend_base . $this->open_class . $this->new_line
            // write classname in variable
            . $this->tab . $this->types[0] . $this->probel . $this->varible_head . "model_name" . $this->varible_value . "'" . $classname . "'" . $this->end_varible . $this->new_line
            // write get function
            . $this->tab . $this->get_function . $this->new_line
            // write update function
            . $this->tab . $this->update_function . $this->new_line
            // write delete function
            . $this->tab . $this->delete_function . $this->new_line
            // write insert function
            . $this->tab . $this->insert_function . $this->new_line
            // write select function
            . $this->tab . $this->select_function . $this->new_line

            . $this->close_class;
        if (file_put_contents($this->directory . "/" . $classname . ".php", $compiled) === FALSE) {
            throw new Exception("Could not write cache file to path '" . $this->directory . "/" . $classname . ".php" . "'. Is it writable?");
        }
        chmod($this->directory . "/" . $classname . ".php", 0755);
    }
}

$generate = new Model();
$generate->directory = "models";
$generate->create_model("Test");
