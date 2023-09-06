<?php


trait Sql_codes
{

     public $get_function = '
    public function get() {
          $stmt = $this->db->query("SELECT * FROM $this->model_name");
          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $data;
     }';

     public $update_function = '
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

     public $delete_function = '
    public function delete($id)
    {
        $sql = "DELETE FROM $this->model_name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }';

     public $insert_function = '
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

     public $select_function = '
    public function select($id)
    {
        $sql = "SELECT * FROM $this->model_name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }';

     public $create_table = '
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
}


trait Base_codes
{
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
}


trait Admin_codes
{
     public $pagination_code = '
if (isset($_GET["old"]) or isset($_GET["next"])) {
     $old_page = $_GET["old"];
     $next_page = $_GET["next"];
     if (isset($next_page)) {
          $model_names = $db->{"get_" . $model_name}($next_page);
          if ($model_names) {
               $old = "?old=" . (string) $next_page - 1;
               $next = "?next=" . (string) $next_page;
               if (count($model_names) > 10) {
                    $next = "?next=" . (string) $next_page + 1;
               }
          }
          $i = ($next_page - 1) * 10;
     } elseif (isset($old_page)) {
          $model_names = $db->{"get_" . $model_name}($old_page);
          if ($model_names) {
               $next = "?next=" . (string) $old_page + 1;
               $old = "#";
               if (count($model_names) > 10) {
                    $old = "?old=" . (string) $old_page - 1;
               }
          }

          $i = $old_page - 1;
          if ($old_page > 1) {
               $i = $i * 10;
          }

     }
} else {
     $model_names = $db->{"get_" . $model_name}();
     $next = "#";
     if (count($model_names) >= 10) {
          $next = "?next=2";
     }
     $old = "#";
     $i = 0;
}';

     public $get_model = '<?php
require "header.php";
require "components/db.php";

$db = new Database();';

     public $get_html = '
     <!-- Content wrapper -->

<div class="content-wrapper">
     <!-- Content -->
     <div class="container-xxl flex-grow-1 container-p-y">
          <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> Users</h4>
          <div class="card">
               <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                         <h5>Users</h5>
                         <div>
                              <a class="btn btn-outline-primary me-2" href="texts.php<?= $old; ?>">
                                   <i class="bx bx-chevron-left"></i>
                              </a>
                              <a class="btn btn-outline-primary" href="texts.php<?= $next; ?>">
                                   <i class="bx bx-chevron-right"></i>
                              </a>
                         </div>
                    </div>
               </div>

               <div class="card-body">
                    <div class="table-responsive text-sm">
                         <table class="table table-bordered">
                              <thead>
                                   <tr class="table-active">
                                        <th>No</th>
                                        <?php $table_heads ?>
                                        <th>Action</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   <?php

                                   foreach ($model_names as $model_name) {
                                        $i++;
                                        ?>
                                        <tr>
                                             <td><i class="fab fa-react fa-lg city-info me-1"></i> <strong>
                                                       <?= $i ?>
                                                  </strong></td>
                                             <?php 
                                             foreach ($table_value as $table_v){
                                                  echo "<td>" . $model_name[$table_v] . "</td>";
                                             } ?>
                                             <td>
                                                  <div class="dropdown">
                                                       <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                       </button>
                                                       <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="edit-<?= $model_name ?>.php?id=<?= $model_name["id"] ?>"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                                            <a class="dropdown-item" href="delete-<?= $model_name ?>.php?id=<?= $model_name["id"] ?>"><i class="bx bx-trash me-1"></i> Delete</a>
                                                       </div>

                                                  </div>

                                             </td>
                                        </tr>
                                        <?php
                                   }

                                   ?>
                              </tbody>
                         </table>
                    </div>
               </div>
          </div>
     </div>
</div>
<!--/ Bordered Table -->';

     public $get_footer = '
<?php require "footer.php"; ?>';

}
