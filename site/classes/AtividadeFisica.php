<?php
    /**
	* Atividade Fisica
	* Functions about a single Atividade Fisica
	*
	* @copyright  Copyright (c) Liquidata. (http://www.liquidata.pt)
	*/
    class AtividadeFisica
    {
        public $id;
        public $title;
        public $description;
        public $selected;           // T/F indicates if it is used by the loggedUser
        
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
            $txt = "SELECT * FROM atividadesFisicas WHERE id = $id";
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
            $this->title = $row["title_pt"];
            $this->description = $row["description_pt"];
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
                "title_pt" => $this->titlet,
                "description_pt" => $this->description,
            );
        
            if (!$this->id) {
                // Insert
                $this->id = $database->insertByArray("atividadesFisicas", $mData);
            } else {
                // Update
                $database->updateByArray("atividadesFisicas", $this->id, $mData);
            }
        }
        
        /**
         * Delete from database
         */
        public function delete()
        {
            $database = new Database();
            
            $txt = "DELETE FROM atividadesFisicas WHERE id = " . $this->id;
            $database->sqlExecute($txt);
        }
    }
