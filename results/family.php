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

        case 1: // �������
            $pair = "���";
            $parent = "����";
            $child = "���";
            $grandparent = "�������";
            $grandchild = "����";
            $uncle = "����";
            $nephew = "���������";
            $sibs = "����";
            $friend = "����";
            $lover = "��������";

            break;

        case 2: // �������
            $pair = "����";
            $parent = "����";
            $child = "����";
            $grandparent = "�������";
            $grandchild = "������";
            $uncle = "Ҹ��";
            $nephew = "����������";
            $sibs = "������";
            $friend = "�������";
            $lover = "���������";

            break;

        default: // �������
            $pair = "���/����";
            $parent = "����/����";
            $child = "���/����";
            $grandparent = "�������/�������";
            $grandchild = "����/������";
            $uncle = "����/���";
            $nephew = "���������/����������";
            $sibs = "����/������";
            $friend = "����/�������";
            $lover = "��������/���������";

            break;
    }

    if ($prop) {
        switch ($rel_nick_sex) {

            case 1: // �������
                $pair = "����";
                break;

            case 2: // �������
                $pair = "���";
                break;

            default: // �������
                $pair = "���/����";
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
        $img = "<img class='fail pointer' id='img" . $v['id'] . "' onclick='family.fail(2," . $v['id'] . ")' src='http://igust4u.ru/service/igust-2.0/img/cancel.png' alt='��������� ���������' />";
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

        $props .= "<li>" . torus($v['rel_type'], $sex, $v['rel_nick_sex']) . ": <span id='s" . $v['id'] . "'>" . $v['rel_nick'] . "</span><img class='pointer' onclick='family.chain(" . $v['id'] . ")' id='ch" . $v['id'] . "' src='http://igust4u.ru/service/igust-2.0/img/unchain.png' alt='��������' /></li>";
        //echo $props;
        $proposals++;
    }
    elseif ($v['rel_status'] == -2) {
        $props2 .= "<li class='white'>" . $v['rel_nick'] . " �������� ���� ����������� (" . torus($v['rel_type'], $sex, $v['rel_nick_sex'], true) . ")</li>";
        $proposals++;
    }
    elseif ($v['rel_status'] == -3) {
        $props3 .= "<li class='white'>" . $v['rel_nick'] . " �������� ��������� (" . torus($v['rel_type'], $sex, $v['rel_nick_sex'], true) . ") � ����</li>";
        $proposals++;
    }

}


$props .= $props2 .= $props3;


// TODO: ������� �������� ���������

$notifications = $proposals;


if ($notifications > 0 && $self_profile != 0) { // ���� ���� ����������� � �� � ����� ������ ��������� TODO: ������ ��� ������ �� �������
    $content .= "<div class='out_notifier pointer' onclick='family.proposals()'><div class='in_notifier'>$notifications</div></div>";
}

if ($rels_chain == "") {
    if ($self_profile != 0) {
        $rels_chain = "�� �� ������ ����������� ����������� � ���� ����.";
    }
    else {
        $rels_chain = "������������ �� ����� ����������� ����������� � ���� ����";
    }
}
$content .= $rels_chain;

if ($self_profile != 0) {

    if ($rels_unchain) {
        $content .= "
<div class='bottom' onclick='family.toggle();'><span id='unchained' class='pointer'>������� �������������</span></div>
<div class='unchained'>
{$rels_unchain}
</div>
";
    }
    $content .= "<br />��������:
<select id='family_fill' onchange='family.sel(this.value);'>
    <option></option>
    <option value='pair'>���� | ����</option>
    <option value='parent'>���� | ����</option>
    <option value='child'>���� | ����</option>
    <option value='grandparent'>������� | �������</option>
    <option value='grandchild'>����� | ������</option>
    <option value='uncle'>���� | Ҹ��</option>
    <option value='nephew'>���������� | ����������</option>
    <option value='sibs'>����� | ������</option>
    <option value='friend'>����� | �������</option>
        <optgroup label='����� �� ������ ;-)'>
    <option value='lover'>��������� | ���������</option>
    </optgroup>
</select>
 
<input class='familytimeback' type='button' onclick='family.timeback();' value='��������' />
<input class='familyadd' type='button' onclick='family.al();' value='����������' />
";


    $proposal = "<div>�����������:</div>
<ul>
   {$props}
</ul>
<input type='button' onclick='family.deproposals();' value='������' />
";


    // TODO: ������� ������ ������

}


$GLOBALS['_RESULT'] = array(
    "content" => $content,
    "proposal" => $proposal
);

?>