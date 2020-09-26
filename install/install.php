<?php

/************************************
*   @author         Mian Saleem     *
*   @package        SPOS v4         *
*   @subpackage     install         *
************************************/

$installFile = "../SPOS4";
$indexFile = "../index.php";
$configFolder = "../app/config";
$configFile = "../app/config/config.php";
$dbFile = "../app/config/database.php";
if (is_file($installFile)) {

    $step = isset($_GET['step']) ? $_GET['step'] : '';
    switch ($step) {
        default:
        ?>
        <ul class="steps">
            <li class="active pk">Requisitos</li>
            <li>Verificação</li>
            <li>Database</li>
            <li>Configuração</li>
            <li class="last">FIM!</li>
        </ul>
        <h3>Lista de Verificação de pré-instalação</h3>
        <?php
        $error = FALSE;
        if (!is_writeable($indexFile)) {
            $error = TRUE;
            echo "<div class='alert alert-error'><i class='icon-remove'></i> O arquivo index (index.php) não é gravável!</div>";
        }
        if (!is_writeable($configFolder)) {
            $error = TRUE;
            echo "<div class='alert alert-error'><i class='icon-remove'></i> A pasta config (app/config/) não é gravável!</div>";
        }
        if (!is_writeable($configFile)) {
            $error = TRUE;
            echo "<div class='alert alert-error'><i class='icon-remove'></i> O arquivo config (app/config/config.php) não é gravável!</div>";
        }
        if (!is_writeable($dbFile)) {
            $error = TRUE;
            echo "<div class='alert alert-error'><i class='icon-remove'></i> O arquivo database (app/config/database.php) não é gravável!</div>";
        }
        if (phpversion() < "5.3") {
            $error = TRUE; echo "<div class='alert alert-error'><i class='icon-remove'></i> Sua versão do PHP é ".phpversion()."! Necessário PHP 5.3 ou superior!</div>";}else{echo "<div class='alert alert-success'><i class='icon-ok'></i> Você está executando o PHP ".phpversion()."</div>";
        }
        if (!extension_loaded('mysqli')) {
            $error = TRUE;
            echo "<div class='alert alert-error'><i class='icon-remove'></i> Extensão Mysqli PHP faltando!</div>";
        } else {
            echo "<div class='alert alert-success'><i class='icon-ok'></i> Extensão Mysqli PHP carregada!</div>";
        }
        if (!extension_loaded('mbstring')) {
            $error = TRUE;
            echo "<div class='alert alert-error'><i class='icon-remove'></i> Extensão MBString PHP faltando!</div>";
        } else {
            echo "<div class='alert alert-success'><i class='icon-ok'></i> Extensão MBString PHP carregada!</div>";
        }
        if (!extension_loaded('gd')) {
            echo "<div class='alert alert-error'><i class='icon-remove'></i> Extensão GD PHP faltando!</div>";
        } else {
            echo "<div class='alert alert-success'><i class='icon-ok'></i> Extensão GD PHP carregada!</div>";
        }
        if (!extension_loaded('curl')) {
            $error = TRUE;
            echo "<div class='alert alert-error'><i class='icon-remove'></i> Extensão CURL PHP faltando!</div>";
        } else {
            echo "<div class='alert alert-success'><i class='icon-ok'></i> Extensão CURL PHP carregada!</div>";
        }
        if (!extension_loaded('zip')) {
            $error = TRUE;
            echo "<div class='alert alert-error'><i class='icon-remove'></i> Extensão ZIP PHP faltando!</div>";
        } else {
            echo "<div class='alert alert-success'><i class='icon-ok'></i> Extensão ZIP PHP carregada!</div>";
        }
        ?>
        <div class="bottom">
            <?php if ($error) { ?>
            <a href="#" class="btn btn-primary disabled">Próximo passo</a>
            <?php } else { ?>
            <a href="index.php?step=0" class="btn btn-primary">Próximo passo</a>
            <?php } ?>
        </div>

        <?php
        break;
        case "0":
        ?>
        <ul class="steps">
            <li class="ok"><i class="icon icon-ok"></i>Requisitos</li>
             <li class="active">Verificação</li>
            <li>Database</li>
            <li>Configuração</li>
            <li class="last">FIM!</li>
        </ul>
        <h3>Confirme sua compra</h3>
        <?php
        if ($_POST) {
            $code = $_POST["code"];
            $username = $_POST["username"];
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, 'https://api.tecdiary.com/v1/license/');
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
            $referer = "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -24);
            $path = substr(realpath(dirname(__FILE__)), 0, -8);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
                'username' => $_POST["username"],
                'code' => $_POST["code"] ,
                'id' => '3947976',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'referer' => $referer,
                'path' => $path
                ));

            $buffer = curl_exec($curl_handle);
            curl_close($curl_handle);
            if (! (is_object(json_decode($buffer)))) {
                $cfc = strip_tags($buffer);
            } else {
                $cfc = NULL;
            }
            $object = json_decode($buffer);

            if ($object->status == 'success') {
                ?>
                <form action="index.php?step=1" method="POST" class="form-horizontal">

                    <div class="alert alert-success"><i class='icon-ok'></i> <strong><?php echo ucfirst($object->status); ?></strong>:<br /><?php echo $object->message; ?></div>
                    <input id="code" type="hidden" name="code" value="<?php echo $code; ?>" />
                    <input id="username" type="hidden" name="username" value="<?php echo $username; ?>" />
                    <div class="bottom">
                        <input type="submit" class="btn btn-primary" value="Próximo passo"/>
                    </div>
                </form>
                <?php
            } else {
                ?>
                <form action="index.php?step=1" method="POST" class="form-horizontal">

                    <div class="alert alert-success"><i class='icon-ok'></i> <strong><?php echo ucfirst($object->status); ?></strong>:<br /><?php echo $object->message; ?></div>
                    <input id="code" type="hidden" name="code" value="<?php echo $code; ?>" />
                    <input id="username" type="hidden" name="username" value="<?php echo $username; ?>" />
                    <div class="bottom">
                        <input type="submit" class="btn btn-primary" value="Próximo passo"/>
                    </div>
                </form>
                <?php
            }
        } else {
            ?>
            <p>Por favor, insira as informações necessárias para confirmar sua compra. </p><br>
            <form action="index.php?step=0" method="POST" class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" for="username">Usuário</label>
                    <div class="controls">
                        <input id="username" type="text" name="username" class="input-large" required data-error="Username is required" placeholder="Envato Username" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="code">Serial </label>
                    <div class="controls">
                        <input id="code" type="text" name="code" class="input-large" required data-error="Purchase Code is required" placeholder="Purchase Code" />
                    </div>
                </div>
                <div class="bottom">
                    <input type="submit" class="btn btn-primary" value="Validate"/>
                </div>
            </form>
            <?php
        }
        break;
        case "1":
        ?>		
		<ul class="steps">
            <li class="ok"><i class="icon icon-ok"></i>Requisitos</li>
            <li class="ok"><i class="icon icon-ok"></i>Verificação</li>
            <li class="active">Database</li>
            <li>Configuração</li>
            <li class="last">FIM!</li>
        </ul>
		
        <?php
        if ($_POST) {
            ?>
            <h3>Configuração do Banco de Dados</h3>
            <p>Se o banco de dados não existir, o sistema tentará criá-lo.</p>
			
            <form action="index.php?step=2" method="POST" class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" for="dbhost">Database Host</label>
                    <div class="controls">
                        <input id="dbhost" type="text" name="dbhost" class="input-large" required data-error="DB Host is required" placeholder="DB Host" value="localhost" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="dbusername">Database Username</label>
                    <div class="controls">
                        <input id="dbusername" type="text" name="dbusername" class="input-large" required data-error="DB Username is required" placeholder="DB Username" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="dbpassword">Database Password</a></label>
                    <div class="controls">
                        <input id="dbpassword" type="password" name="dbpassword" class="input-large" data-error="DB Password is required" placeholder="DB Password" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="dbname">Database Name</label>
                    <div class="controls">
                        <input id="dbname" type="text" name="dbname" class="input-large" required data-error="DB Name is required" placeholder="DB Name" />
                    </div>
                </div>
					<div class="alert alert-warning"><i class='icon-warning-sign'></i> restaure o banco de dados manualmente antes de acessar o site.</div>
					
                <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
                <input type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                <div class="bottom">
                    <input type="submit" class="btn btn-primary" value="Próximo passo"/>
                </div>
            </form>
            <?php
        }
        break;
        case "2":
        ?>
        <ul class="steps">
            <li class="ok"><i class="icon icon-ok"></i>Requisitos</li>
            <li class="ok"><i class="icon icon-ok"></i>Verificação</li>
            <li class="active">Database</li>
            <li>Configuração</li>
            <li class="last">FIM!</li>
        </ul>
        <h3>Saving database config</h3>
        <?php
        if ($_POST) {
            $dbhost = $_POST["dbhost"];
            $dbusername = $_POST["dbusername"];
            $dbpassword = $_POST["dbpassword"];
            $dbname = $_POST["dbname"];
            $code = $_POST["code"];
            $username = $_POST["username"];
            $link = new mysqli($dbhost, $dbusername, $dbpassword);
            if (mysqli_connect_errno()) {
                echo "<div class='alert alert-error'><i class='icon-remove'></i> Could not connect to MYSQL!</div>";
            } else {
                echo '<div class="alert alert-success"><i class="icon-ok"></i> Connection to MYSQL successful!</div>';
                $db_selected = mysqli_select_db($link, $dbname);
                if (!$db_selected) {
                    if (!mysqli_query($link, "CREATE DATABASE IF NOT EXISTS `$dbname`")) {
                        echo "<div class='alert alert-error'><i class='icon-remove'></i> Database " . $dbname . " does not exist and could not be created. Please create the Database manually and retry this step.</div>";
                        return FALSE;
                    } else {
                        echo "<div class='alert alert-success'><i class='icon-ok'></i> Database " . $dbname . " created</div>";
                    }
                }
                mysqli_select_db($link, $dbname);

                require_once('includes/core_class.php');
                $core = new Core();
                $dbdata = array(
                    'hostname' => $dbhost,
                    'username' => $dbusername,
                    'password' => $dbpassword,
                    'database' => $dbname
                    );

                if ($core->write_database($dbdata) == false) {
                    echo "<div class='alert alert-error'><i class='icon-remove'></i> Falha ao gravar detalhes do banco de dados para ".$dbFile."</div>";
                } else {
                    echo "<div class='alert alert-success'><i class='icon-ok'></i> Configuração do banco de dados gravada no arquivo do banco de dados.</div>";
                }

            }
        } else { echo "<div class='alert alert-success'><i class='icon-question-sign'></i> Nothing to do...</div>"; }
        ?>
		
		
        <div class="bottom">
            <form action="index.php?step=1" method="POST" class="form-horizontal">
                <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
                <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                <input type="submit" class="btn pull-left" value="Passo anterior"/>
            </form>
            <form action="index.php?step=3" method="POST" class="form-horizontal">
                <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
                <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                <input type="submit" class="btn btn-primary pull-right" value="Próximo passo">
            </form>
            <br clear="all">
        </div>
        <?php
        break;
        case "3":
        ?>
        <ul class="steps">
            <li class="ok"><i class="icon icon-ok"></i>Requisitos</li>
            <li class="ok"><i class="icon icon-ok"></i>Verificação</li>
            <li class="ok"><i class="icon icon-ok">Database</li>
            <li class="active">Configuração</li>
            <li class="last">FIM!</li>
        </ul>
        <h3>Configuração do site</h3>
        <?php
        if ($_POST) {
            ?>
            <form action="index.php?step=4" method="POST" class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" for="domain">Base URL</label>
                    <div class="controls">
                        <input type="text" id="domain" name="domain" class="xlarge" required data-error="Base URL is required" value="<?php echo "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -24); ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="domain">SECRET KEY</label>
                    <div class="controls">
                        <?php $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; ?>
                        <input type="text" id="enckey" name="enckey" class="xlarge" required data-error="SECRET KEY is required" value="<?php echo substr(str_shuffle($characters), 32); ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="domain">Seu fuso horário</a></label>
                    <div class="controls">
                        <?php
                        $timezones = DateTimeZone::listIdentifiers();
                        echo '<select name="timezone" required="required" data-error="O fuso horário é obrigatório">';
                        foreach ($timezones as $tz){
                            echo '<option value="'.$tz.'">'.$tz.'</option>';
                        }
                        echo '</select>'; ?>
                    </div>
                </div>
                <input type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
                <input type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                <div class="bottom">
                    <a href="index.php?step=2" class="btn pull-left">Passo anterior</a>
                    <input type="submit" class="btn btn-primary" value="Próximo passo"/>
                </div>
            </form>

            <?php
        }
        break;
        case "4":
        ?>
		
        <ul class="steps">
            <li class="ok"><i class="icon icon-ok"></i>Requisitos</li>
            <li class="ok"><i class="icon icon-ok"></i>Verificação</li>
            <li class="ok"><i class="icon icon-ok">Database</li>
            <li class="active">Configuração</li>
            <li class="last">FIM!</li>
        </ul>
		
        <h3>Salvando a configuração do site</h3>
        <?php
        if ($_POST) {
            $domain = $_POST['domain'];
            $enckey = $_POST['enckey'];
            $timezone = $_POST['timezone'];
            $code = $_POST["code"];
            $username = $_POST["username"];

            require_once('includes/core_class.php');
            $core = new Core();

            if ($core->write_config($domain, $enckey) == false) {
                echo "<div class='alert alert-error'><i class='icon-remove'></i> Falha ao gravar detalhes de configuração para ".$configFile."</div>";
            } elseif ($core->write_index($timezone) == false) {
                echo "<div class='alert alert-error'><i class='icon-remove'></i> Falha ao escrever os detalhes do fuso horário para ".$indexFile."</div>";
            } else {
                echo "<div class='alert alert-success'><i class='icon-ok'></i> Detalhes da configuração gravados no arquivo de configuração.</div>";
            }

        } else { echo "<div class='alert alert-success'><i class='icon-question-sign'></i> Nada para fazer...</div>"; }
        ?>
        <div class="bottom">
            <form action="index.php?step=2" method="POST" class="form-horizontal">
                <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
                <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                <input type="submit" class="btn pull-left" value="Passo anterior"/>
            </form>
            <form action="index.php?step=5" method="POST" class="form-horizontal">
                <input id="code" type="hidden" name="code" value="<?php echo $_POST['code']; ?>" />
                <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                <input type="submit" class="btn btn-primary pull-right" value="Próximo passo">
            </form>
            <br clear="all">
        </div>

        <?php
        break;
        case "5":
        ?>
        <ul class="steps">
            <li class="ok"><i class="icon icon-ok"></i>Requisitos</li>
            <li class="ok"><i class="icon icon-ok"></i>Verificação</li>
            <li class="ok"><i class="icon icon-ok"></i>Database</li>
            <li class="ok"><i class="icon icon-ok"></i>Configuração</li>
            <li  class="active">FIM!</li>
        </ul>

        <?php
        if ($_POST) {
            $code = $_POST['code'];
            $username = $_POST['username'];
            define("BASEPATH", "install/");
            include("../app/config/database.php");
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, 'https://ai.tecdiary.com/v1/dbtables/');
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
                'username' => $_POST["username"],
                'code' => $_POST["code"] ,
                'id' => '3947976',
                'version' => '4.0',
                'type' => 'install'
                ));
            $buffer = curl_exec($curl_handle);
            curl_close($curl_handle);
            $object = json_decode($buffer);

            if ($object->status == !'success') {
                $dbdata = array(
                    'hostname' => $db['default']['hostname'],
                    'username' => $db['default']['username'],
                    'password' => $db['default']['password'],
                    'database' => $db['default']['database'],
                    'dbtables' => $object->database
                    );
                require_once('includes/database_class.php');
                $database = new Database();
                if ($database->create_tables($dbdata, $_POST['username'], $_POST['code']) == false) {
                    $finished = FALSE;
                    echo "<div class='alert alert-warning'><i class='icon-warning'></i> As tabelas do banco de dados não puderam ser criadas. Tente novamente.</div>";
                } else {
                    $finished = TRUE;
                    if (!@unlink('../SPSO4')){
                        echo "<div class='alert alert-warning'><i class='icon-warning'></i> Por favor, remova o arquivo SPOS4 da pasta principal para bloquear o instalador.</div>";
                    }

                }
            } else {
                echo "<div class='alert alert-error'><i class='icon-remove'></i> Erro ao validar seu serial de compra!</div>";
            }

        }
        if ($finished) {
            ?>
            <h3><i class='icon-ok'></i> Quase completo</h3>
            <div class="alert alert-info"><i class='icon-info-sign'></i> Você pode fazer o login agora usando a seguinte credencial:<br /><br />
                Email: <span style="font-weight:bold; letter-spacing:1px;">admin@admin.com</span><br />Senha: <span style="font-weight:bold; letter-spacing:1px;">12345678</span><br /><br />
            </div>
            <div class="alert alert-warning"><i class='icon-warning-sign'></i> restaure o banco de dados manualmente antes de acessar o site.</div>
            <div class="bottom">
                <a href="<?php echo "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -24); ?>" class="btn btn-primary">Fazer login</a>
            </div>
            <?php
        }
    }

} else {
    echo "<div style='width: 100%; font-size: 10em; color: #757575; text-shadow: 0 0 2px #333, 0 0 2px #333, 0 0 2px #333; text-align: center;'><i class='icon-lock'></i></div><h3 class='alert-text text-center'>O instalador está bloqueado!<br><small style='color:#666;'>Entre em contato com seu vendedor/suporte</small></h3>";
}
?>

<!-- Modal 
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
        <h3 id="myModalLabel">How to find your purchase code</h3>
    </div>
    <div class="modal-body">
        <img src="img/purchaseCode.png">
    </div>
</div>
-->