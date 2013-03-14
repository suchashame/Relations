<?php


$rels_chain = $rels_unchain = $props = $props2 = $props3 = $content = $proposal = $img = "";


$proposals = 0;


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

function torus($type, $nick_sex = 0, $rel_nick_sex = 0, $prop = false)
{

    if ($prop) {
        $type = reltype($type);
    }

    switch ($rel_nick_sex) {

        case 1: // Мужской
            $pair = "Муж";
            $parent = "Папа";
            $child = "Сын";
            $grandparent = "Дедушка";
            $grandchild = "Внук";
            $uncle = "Дядя";
            $nephew = "Племянник";
            $sibs = "Брат";
            $friend = "Друг";
            $lover = "Любовник";

            break;

        case 2: // Женский
            $pair = "Жена";
            $parent = "Мама";
            $child = "Дочь";
            $grandparent = "Бабушка";
            $grandchild = "Внучка";
            $uncle = "Тётя";
            $nephew = "Племянница";
            $sibs = "Сестра";
            $friend = "Подруга";
            $lover = "Любовница";

            break;

        default: // Средний
            $pair = "Муж/жена";
            $parent = "Папа/мама";
            $child = "Сын/дочь";
            $grandparent = "Дедушка/бабушка";
            $grandchild = "Внук/внучка";
            $uncle = "Дядя/тётя";
            $nephew = "Племянник/племянница";
            $sibs = "Брат/сестра";
            $friend = "Друг/подруга";
            $lover = "Любовник/любовница";

            break;
    }

    if ($prop) {
        switch ($rel_nick_sex) {

            case 1: // Мужской
                $pair = "Жена";
                break;

            case 2: // Женский
                $pair = "Муж";
                break;

            default: // Средний
                $pair = "Муж/жена";
                break;
        }
    }


    $torus = array("pair" => $pair,
        "parent" => $parent,
        "child" => $child,
        "grandparent" => $grandparent,
        "grandchild" => $grandchild,
        "uncle" => $uncle,
        "nephew" => $nephew,
        "sibs" => $sibs,
        "friend" => $friend,
        "lover" => $lover,
    );


    return $torus[$type];


}


foreach ($relations as $k => $v) {

    if ($self_profile != 0) {
        $img = "<img class='fail pointer' id='img" . $v['id'] . "' onclick='family.fail(2," . $v['id'] . ")' src='http://igust4u.ru/service/igust-2.0/img/cancel.png' alt='Разорвать отношения' />";
    }

    if ($v['rel_status'] == 1) {

        if ($v['rel_type'] == "lover" && ($id != $v['rel_nick_id'] && $id != $v['nick_id'])) {

            continue;
        }


        $rels_chain .= "<div onmouseover='family.fail(1," . $v['id'] . ")' ' onmouseout='family.fail(0," . $v['id'] . ")' >" .
            torus($v['rel_type'], $sex, $v['rel_nick_sex']) . ": " . "<a id='a" . $v['id'] . "'
        href='/people/info?profile=" .
            $v['rel_nick_id'] . "' target='august_profile'
            onclick='root.August.userInfo(" . $v['rel_nick_id'] . ", 2);return false;'>" .
            $v['rel_nick']
            . "</a>$img</div>";
    }
    elseif ($v['rel_status'] == 0) {
        //$rels_unchain .= "<div>" . torus($v['rel_type'],$sex,$v['rel_nick_sex']) . ": " . $v['rel_nick'] . "$img</div>";
        $rels_unchain .= "<div onmouseover='family.fail(1," . $v['id'] . ")' onmouseout='family.fail(0," . $v['id'] . ")' >" .
            torus($v['rel_type'], $sex, $v['rel_nick_sex']) . ": "
            . "<a id='a" . $v['id'] . "'
                href='/people/info?profile=" .
            $v['rel_nick_id'] . "' target='august_profile'
                    onclick='root.August.userInfo(" .
            $v['rel_nick_id'] . ", 2);return false;'>" .
            $v['rel_nick']
            . "</a>$img</div>";

    }

    elseif ($v['rel_status'] == -1) {

        $props .= "<li>" . torus($v['rel_type'], $sex, $v['rel_nick_sex']) . ": <span id='s" . $v['id'] . "'>" . $v['rel_nick'] . "</span><img class='pointer' onclick='family.chain(" . $v['id'] . ")' id='ch" . $v['id'] . "' src='http://igust4u.ru/service/igust-2.0/img/unchain.png' alt='Согласен' /></li>";
        //echo $props;
        $proposals++;
    }
    elseif ($v['rel_status'] == -2) {
        $props2 .= "<li class='white'>" . $v['rel_nick'] . " отклонил ваше предложение (" . torus($v['rel_type'], $sex, $v['rel_nick_sex'], true) . ")</li>";
        $proposals++;
    }
    elseif ($v['rel_status'] == -3) {
        $props3 .= "<li class='white'>" . $v['rel_nick'] . " разорвал отношения (" . torus($v['rel_type'], $sex, $v['rel_nick_sex'], true) . ") с вами</li>";
        $proposals++;
    }

}


$props .= $props2 .= $props3;


// TODO: Сделать картинки спрайтами

$notifications = $proposals;


if ($notifications > 0 && $self_profile != 0) { // Если есть предложения и вы в своей анкете — вывести TODO: Убрать всю логику из шаблона
    $content .= "<div class='out_notifier pointer' onclick='family.proposals()'><div class='in_notifier'>$notifications</div></div>";
}

if ($rels_chain == "") {
    if ($self_profile != 0) {
        $rels_chain = "Вы не успели обзавестись отношениями в этом чате.";
    }
    else {
        $rels_chain = "Пользователь не успел обзавестись отношениями в этом чате";
    }
}
$content .= $rels_chain;

if ($self_profile != 0) {

    if ($rels_unchain) {
        $content .= "
<div class='bottom' onclick='family.toggle();'><span id='unchained' class='pointer'>Ожидают подтверждения</span></div>
<div class='unchained'>
{$rels_unchain}
</div>
";
    }
    $content .= "<br />Добавить:
<select id='family_fill' onchange='family.sel(this.value);'>
    <option></option>
    <option value='pair'>Мужа | Жену</option>
    <option value='parent'>Папу | Маму</option>
    <option value='child'>Сына | Дочь</option>
    <option value='grandparent'>Дедушку | Бабушку</option>
    <option value='grandchild'>Внука | Внучку</option>
    <option value='uncle'>Дядю | Тётю</option>
    <option value='nephew'>Племянника | Племянницу</option>
    <option value='sibs'>Брата | Сестру</option>
    <option value='friend'>Друга | Подругу</option>
        <optgroup label='Никто не увидит ;-)'>
    <option value='lover'>Любовника | Любовницу</option>
    </optgroup>
</select>
 
<input class='familytimeback' type='button' onclick='family.timeback();' value='Сбросить' />
<input class='familyadd' type='button' onclick='family.al();' value='Пригласить' />
";


    $proposal = "<div>Предложения:</div>
<ul>
   {$props}
</ul>
<input type='button' onclick='family.deproposals();' value='Готово' />
";


    // TODO: Сделать кнопку справа

}


$GLOBALS['_RESULT'] = array(
    "content" => $content,
    "proposal" => $proposal
);

?>