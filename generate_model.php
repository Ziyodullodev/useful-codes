<?php

// generate model class
require "trits.php";
class Model
{
    use Sql_codes, Base_codes, Admin_codes;
    public $directory = "models";

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


    public function create_admin($classname, $table_heads = [], $table_values = []){
        $model_names = $classname . "s";
        $table_head = "";
        foreach ($table_heads as $head) {
            $table_head .= "<th>" . $head . "</th>" . $this->new_line;
        }
        $table_value = "";
        foreach ($table_values as $value) {
            $table_value .= "'" . $value . "'," . $this->new_line;
        }

        $compiled =
        // write php open tag
        $this->open_php . $this->new_line . $this->new_line . $this->new_line
        . $this->probel . $this->varible_head . "model_name" . $this->varible_value . "'" . $classname . "'" . $this->end_varible . $this->new_line
        . $this->probel . $this->varible_head . "model_names" . $this->varible_value . "'" . $model_names . "'" . $this->end_varible . $this->new_line
        . $this->probel . $this->varible_head . "table_head" . $this->varible_value . '"' . $table_head . '"' . $this->end_varible . $this->new_line
        . $this->probel . $this->varible_head . "table_values" . $this->varible_value . '[' . $table_value . ']' . $this->end_varible . $this->new_line
        // write pagination
        . $this->pagination_code . $this->new_line . $this->close_php
        // write require base class
        . $this->get_html . $this->new_line . $this->new_line


        // write php close tag
        . $this->get_footer . $this->new_line . $this->new_line
        . $this->new_line . $this->close_php;
    if (file_put_contents($this->directory . "/" . $classname . ".php", $compiled) === FALSE) {
        throw new Exception("Could not write cache file to path '" . $this->directory . "/" . $classname . ".php" . "'. Is it writable?");
    }
    chmod($this->directory . "/" . $classname . ".php", 0755);
    }
}

$generate = new Model();
$generate->directory = "migrations";
$generate->create_admin("text", ["id", "title", "text", "active", "create_at"], ["id", "title", "text", "active", "create_at"] );
