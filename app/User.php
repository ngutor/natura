<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    //protected $table = 'users';
    protected $table = "seg_user";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = "v_Codusuario";
    protected $fillable = ["v_Apellidos","v_Nombres","c_TipoDocide","v_NroDocide","v_Email","v_Telefonos","v_IdPerCliente","v_IdPerClientePadre","i_CodCliente","i_CodContacto","c_TipoUsuario","v_CodPerEmpresa","v_CodEstado","i_CodTipoPerfil","v_PerClienteAgrupa1","v_PerClienteAgrupa2","v_PerClienteAgrupa3"];
    /*protected $primaryKey = "id_usuario";
    protected $fillable = ["cod_usuario","des_apellidos","des_nombres","es_vigencia","tp_cliente","token"];*/

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
}
