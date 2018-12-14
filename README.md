#sso server  
sso extend laravel 5.0.*
>  ####1.import mrsso.sql 
       > * MYSQL
>  ####2.setting apache  
		<VirtualHost _default_:19999>  
		DocumentRoot "E:\www\yadan_sso\public"     
		  <Directory "E:\www\yadan_sso\public"> 
			Options -Indexes +FollowSymLinks +ExecCGI 
			AllowOverride All 
			Order allow,deny 
			Allow from all 
			Require all granted 
		  </Directory> 
		</VirtualHost>  
		
>  ####3.Setting Edit <.env> file ;
       > * for datebase config 
>  ####4.debug && END
