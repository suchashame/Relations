<?php

class Service_Family extends Service_Base
{
    protected $relations;
    protected $proposals;

    function index()
    {
        $self_profile = 0;

        if ($this->nick_id == $this->user_id) {
            $this->delNotify();
            $self_profile = 1;
        }

        $this->getFromDB();

        $this->result->set("self_profile", $self_profile);
        $this->result->set("id", $this->user_id);
        $this->result->set("sex", $this->sex);
        $this->result->set("relations", $this->relations);
        $this->result->set("proposals", $this->proposals);

        $this->result->show('family');
    }

    function denot()
    {

        if ($this->nick_id != $this->user_id) {
            die("Все действия только из своей анкеты!");
        }

        $this->DB->query("UPDATE family_relations
                                                                      SET rel_show=?,
                                                                      rel_status=?

                                                                      WHERE chat  = ?
                                                                        AND   chat_id = ?
                                                                        AND nick = ?
                                                                        AND (rel_status=? OR rel_status=?)

                                                                        ",

            0, 0, $this->chat, $this->chat_id, $this->nick, -2, -3
        );


        $this->index();

    }

    function fail()
    {

        if (isset($_REQUEST['info'])) {
            $id = $_REQUEST['info'];
        }


        $some = $this->DB->select('SELECT *
                                                    FROM family_relations
                                                    WHERE id  = ?',

            $id
        );
        $rel_id = $some[0]["rel_id"];
        $not_id = $some[0]["rel_nick_id"];

        $this->DB->query("UPDATE family_relations
                                                              SET rel_status=?,
                                                              rel_show=?

                                                              WHERE id = ?",

            0, 0, $id);

        $this->DB->query("UPDATE family_relations
                                                                          SET rel_status=?

                                                                          WHERE id = ?",

            -3, $rel_id);


        $this->addNotify($not_id);

        $this->index();
    }

    function chain($tochain)
    {
        foreach ($tochain as $k => $id) {
            $some = $this->DB->select('SELECT *
                                            FROM family_relations
                                            WHERE id  = ?',

                $id
            );


            $rel_id = $some[0]["rel_id"];


            $this->DB->query("UPDATE family_relations
                                                      SET rel_status=?,
                                                      nick_id=?,
                                                      nick_sex=?

                                                      WHERE id = ?",

                1, $this->nick_id, $this->sex, $id);

            $this->DB->query("UPDATE family_relations
                                                                  SET rel_id=?,
                                                                  rel_status=?,
                                                                  rel_nick_id=?,
                                                                  rel_nick_sex=?

                                                                  WHERE id = ?",

                $id, 1, $this->nick_id, $this->sex, $rel_id);

        }
    }

    function unchain($tounchain)
    {
        foreach ($tounchain as $k => $id) {

            $some = $this->DB->select('SELECT *
                                                       FROM family_relations
                                                       WHERE id  = ?',

                $id
            );


            $rel_id = $some[0]["rel_id"];
            $not_id = $some[0]["rel_nick_id"];

            $this->DB->query("UPDATE family_relations
                                                                 SET rel_status=?,
                                                                 rel_show=?
                                                                 WHERE id = ?",

                0, 0, $id);

            $this->DB->query("UPDATE family_relations
                                                                             SET rel_id=?,
                                                                             rel_status=?,
                                                                             rel_nick_id=?,
                                                                             rel_nick_sex=?

                                                                             WHERE id = ?",

                $id, -2, $this->nick_id, $this->sex, $rel_id);
            $this->addNotify($not_id);


        }
    }

    function chainunchain()
    {
        if ($this->nick_id != $this->user_id) {
            die("Все действия только из своей анкеты!");
        }

        if (isset($_REQUEST['info'])) {
            $add = $_REQUEST['info'];
        }
        //var_dump($add);

        if (isset($add[0])) {
            $this->chain($add[0]);
        }

        if (isset($add[1])) {
            $this->unchain($add[1]);
        }

        $this->index();
    }

    function reltype($rel_type)
    {

        switch ($rel_type) {
            case "parent":
                return "child";
                break;
            case "child":
                return "parent";
                break;
            case "grandparent":
                return "grandchild";
                break;
            case "grandchild":
                return "grandchild";
                break;
            case "uncle":
                return "nephew";
                break;
            case "nephew":
                return "uncle";
                break;
            default:
                return $rel_type;
        }


    }

    function add()
    {
        if ($this->nick_id != $this->user_id) {
            die("Все действия только из своей анкеты!");
        }

        $this->getFromDB();

        $add = $_REQUEST['info'];
        //var_dump($add);

        foreach ($add as $type => $nick) {

            if ($nick == $this->nick) {
                die("Нельзя создавать отношения с самим собой!");
            }
            if ($nick == "") {
                die("В поле $type не был указан ник.");
            }

            $this->addToDB($type, $nick);

        }

        $this->index();
    }

    function getFromDB()
    {

        $this->relations = $this->DB->select('SELECT *
                                              FROM family_relations
                                              WHERE chat_id  = ?
                                              AND   (nick_id  = ? OR nick  = ?)
                                              AND   rel_show = ?',
            $this->chat_id,
            $this->nick_id,
            $this->nick,
            1
        );

        return 0;


    }

    function addToDB($type, $nick)
    {
        if (!$this->typeCheck($type)) {
            die("Нет!");
        }


        $test = $this->DB->select('SELECT *
                             FROM family_relations
                               WHERE chat_id  = ?
                               AND   nick  = ?
                               AND nick_id = ?

                               AND rel_type = ?
                               AND rel_nick = ?
                               AND   rel_show = ?',
            $this->chat_id,
            $this->nick,
            $this->nick_id,

            $type,
            $nick,
            1
        );

        if (empty($test)) {


            $insert_relations1 = array(
                "chat" => htmlspecialchars($this->chat),
                "chat_id" => htmlspecialchars($this->chat_id),
                "nick" => htmlspecialchars($this->nick),
                "nick_id" => htmlspecialchars($this->nick_id),
                "nick_sex" => htmlspecialchars($this->sex),
                "rel_type" => htmlspecialchars($type),
                "rel_nick" => htmlspecialchars($nick),
                "rel_status" => 0,

            );


            $this->DB->query('INSERT INTO family_relations(?#) VALUES(?a)',
                array_keys($insert_relations1),
                array_values($insert_relations1)
            );


            $test = $this->DB->select('SELECT *
                                         FROM family_relations
                                           WHERE chat_id  = ?
                                           AND   nick  = ?
                                           AND nick_id = ?

                                           AND rel_type = ?
                                           AND rel_nick = ?
                                           AND   rel_show = ?',
                $this->chat_id,
                $this->nick,
                $this->nick_id,

                $type,
                $nick,
                1
            );
            $id = $test[0]["id"];
            //  echo $id;


            $insert_relations2 = array(
                "rel_id" => $id,
                "chat" => htmlspecialchars($this->chat),
                "chat_id" => htmlspecialchars($this->chat_id),
                "nick" => htmlspecialchars($nick),


                "rel_type" => $this->reltype($type),
                "rel_nick" => htmlspecialchars($this->nick),
                "rel_nick_id" => htmlspecialchars($this->nick_id),
                "rel_nick_sex" => htmlspecialchars($this->sex),
                "rel_status" => "-1",

            );


            $this->DB->query('INSERT INTO family_relations(?#) VALUES(?a)',
                array_keys($insert_relations2),
                array_values($insert_relations2)
            );


            $this->addNotify(0, $nick);


        }
        else {
            die("Незачем добавлять одного пользователя дважды.");

        }

    }

    function typeCheck($type)
    {

        if ($type == "pair" ||
            $type == "parent" ||
            $type == "child" ||
            $type == "grandparent" ||
            $type == "grandchild" ||
            $type == "uncle" ||
            $type == "nephew" ||
            $type == "friend" ||
            $type == "lover" ||
            $type == "sibs"
        ) {
            return true;
        } else {
            return false;
        }


    }

    function addNotify($nick_id, $nick = 0)
    {

        $notify = array(
            "service" => "family",
            "chat_id" => htmlspecialchars($this->chat_id),
            "nick_id" => htmlspecialchars($nick_id),
            "nick" => htmlspecialchars($nick),
            "notify_show" => 1
        );
        $this->DB->query('INSERT INTO notify(?#) VALUES(?a)',
            array_keys($notify),
            array_values($notify)
        );

    }

    function delNotify()
    {

        $this->DB->query("UPDATE notify
                                           SET notify_show=?
                                           WHERE service  = ?
                                             AND   chat_id = ?
                                             AND    (nick = ? OR nick_id = ?)
                                             AND notify_show = ?",
            0, "family", htmlspecialchars($this->chat_id), htmlspecialchars($this->nick), htmlspecialchars($this->nick_id), 1);

    }

}
