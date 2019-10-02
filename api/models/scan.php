<?php

class scan {
    private function savePdf($filename) {
        if (move_uploaded_file($_FILES["scan"]["tmp_name"], $filename)) {
            echo "success";
        } else {
            echo "error";
        }
    }

    public function subjectname($id) {
        $query = Db::query("SELECT name FROM subject WHERE id = :id", [':id' => $id]);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
    }

    private function get($id) {
        $query = Db::query("SELECT * FROM scan WHERE id = :id", [':id' => $id]);

        if ($query->execute()) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result;
            } else {
                return false;
            }
        }
    }

    private function formatTime()
    {
        return [
            'date' => date('j. n. Y', strtotime($this->post_date)),
            'time' => date('H:i', strtotime($this->post_date))
        ];
    }

    public function time($id) {
        
    }

    public function subjects() {
        $query = Db::query("SELECT * FROM subject");
        $query->execute();
        $result = $query->fetchAll();
        echo json_encode($result);
    }

    public function save() {
        $data = json_decode($_POST['data'], true);
        $name = $data['name'];
        $subject = $data['subject'];

        $scan_path = 'scan/' . $name . '.pdf';

        $stmt = Db::getConn()->prepare(
            'INSERT INTO scan(name, subject, time, scan_path)
            VALUES(:name, :subject, CURRENT_TIMESTAMP, :scan_path)'
        );

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':scan_path', $scan_path);

        if ($stmt->execute()) {
            echo 'success';
            $this->savePdf($scan_path);
        } else {
            echo 'error';
        }

    }

    public function id($id) {
        echo json_encode($this->get($id));
    }

    public function all() {
        $query = Db::query("SELECT * FROM scan");

        if ($query->execute()) {
            $result = $query->fetchAll();
            
            if ($result) {
                echo json_encode($result);
            } else {
                echo "false";
            }
        }
    }
    public function pdf($id) {
        $scan = $this->get($id);
        echo file_get_contents($scan['scan_path']);
    }
}