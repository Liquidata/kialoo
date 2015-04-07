<?php
    /**
     * Group
     * Class of table
     *
     * @copyright   Copyright  (c)  Liquidata . (http://www.liquidata.pt)
     */

    class Group
    {

        public $id;                                       // 
        public $name_pt;                                  // 

        public function __construct($id = null) 
        {
        
           if ($id) { 
               $this->initById($id);
           }
        }
        
        /**
         * Start by ID
         */
        public function initById($id)
        {
            $database = new Database();
            $txt = "SELECT * FROM groups WHERE id = $id";
            $database->sqlGet($txt);
            if ($row = $database->sqlRow()) {
                $this->initByRow($row);
            }
        }
        
        /**
         * Start by ROW
         */
        public function initByRow($row)
        {
            global $base;
            
            $database = new Database(false);
            
            // Set properties
            $this->id = $row["id"];
            $this->name_pt = $row["name_pt"];
        }
        
        /**
         * Insert/Update in database
         */
        public function save()
        {
            global $base;
            
            $database = new Database();
            
            // Create array
            $mData = array(
                "name_pt" => $this->name_pt,
            );
        
            if (!$this->id) {
                // Insert
                $this->id = $database->insertByArray("groups", $mData);
            } else {
                // Update
                $database->updateByArray("groups", $this->id, $mData);
            }
        }
        
        /**
         * Delete from database
         */
        public function delete()
        {
            $database = new Database();
            
            $txt = "DELETE FROM groups WHERE id = " . $this->id;
            $database->sqlExecute($txt);
        }
}