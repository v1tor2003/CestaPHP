<!--;
/*
Nome: Vagner Luz do Carmo - (nick: Vluzrmos)
Graduando em Ci�ncias da Computa��o - UESC
Desenvolvedor WEB: PHP, MySQL, JavaScript, XHTML Strict 1.0
Celular: +55(73)8128-9319
Emails:
vagner_g@hotmail.com  (e msn tamb�m)
vluzrmos@gmail.com    (email principal)
		
Ilh�us-Bahia, Brasil
*/

errmsg      = 'Usu&aacute;rio ou senha inv&aacute;lidos!';
errmsg2     = "Usu&aacute;rio ou senha est&atilde;o vazios!";
//pagina padrao para pedidos
requestPage = "mylogin.php";
//pagina padrao para usuario logar
adminIndex  = "area_administrativa/index.php";
//id do elemento html onde serao exibidas as msgs de erro
erroLoginId = "errologin";
      
//Mensagem padrao exibida no campo Nome...
defMsgUsr   = "Digite seu Usuário";
	  
//tempo padrao de espera entre as anima��es (slideUp e slideDown)
defDelay    = 500;
	  
$(document).ready(function(){
    vlrDefNome = $("input[name='nome']").val();
    vlrDefSenha = $("input[name='senha']").val();
    $("#"+erroLoginId).hide();
			
    $(".text").focus(
        function(){
            $("#"+erroLoginId).css("visibility","visible");
            $("#"+erroLoginId).slideUp(defDelay);
            $(".text").css("color","black");
        }
        )
		   
    $("input[name='nome']").focus(
        function(){
            $("input[name='nome']").val("");
        }
        )
		   
    $("input[name='nome']").ready(
        function(){
            $("input[name='nome']").val(defMsgUsr);
        }
        )
    $("input[name='senha']").focus(
        function(){
            $("input[name='senha']").val("");
        }
        )
    //recebe formulario submetido
    $('form').submit(function(){
        //verifica se os valoress estao vazios
        if($('input[name="nome"]').val()=='' || $('input[name="senha"]').val()==''){
            $("#"+erroLoginId).slideDown(defDelay);
            //se os valores estiverem vazios exibe a msg padrao de erro para campos vazios.
            $("#"+erroLoginId).html(errmsg2);
            return false;
        }
        else if($('input[name="nome"]').val()==vlrDefNome || $('input[name="senha"]').val()==vlrDefSenha){
            $("#"+erroLoginId).slideDown(defDelay);
            $("#"+erroLoginId).html(errmsg2);
            return false;
        }
        else
        {
				
            ajax_login($('input[name="nome"]').val(),$('input[name="senha"]').val(),requestPage,adminIndex,erroLoginId);
        }
    })
		   		
}
);
	  

	  
	  
	  
function ajax_login(login, senha, phplogin, phpdirect, htmlerrId)
{
	    
    XMLHttp = cria_request(); //funcao em ajax.js
    XMLHttp.open("POST",phplogin,true);
    XMLHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    XMLHttp.send("nome="+login+"&senha="+senha);
		
    XMLHttp.onreadystatechange=function(){
        if (XMLHttp.readyState==4 && XMLHttp.status==200)
        {
            if(XMLHttp.responseText == "0")
            {
                $("#"+htmlerrId).slideDown(defDelay);
                document.getElementById(htmlerrId).innerHTML=errmsg;

            }
            else if(XMLHttp.responseText == "1")
                window.location.assign(phpdirect);
        }
    }
}
	

//-->