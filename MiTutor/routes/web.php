<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Livewire\MiInformacionE;
use App\Livewire\MiInformacionEEditar;
use App\Livewire\LoginForm;
use App\Http\Middleware\RemoveUserIdFromSession;
use App\Livewire\ExamenvPreguntas;
use App\Livewire\ExamenvResultados;
use App\Models\ExamenVocacional;
use App\Models\Tutorado;

Route::get('/examenv-preguntas', ExamenvPreguntas::class)
    ->middleware('auth')
    ->name('examenv.preguntas');


Route::get('/examenv-resultados', function () {
    $curp = session('curp');
    
    if (!$curp) {
        return view('livewire.error-view')->with('error', 'Sesión no válida');
    }

    $tutorado = Tutorado::where('curp', $curp)->first();
    
    if (!$tutorado) {
        return view('livewire.error-view')->with('error', 'Tutorado no encontrado');
    }

    $fieldKey = $tutorado->idCuentaTutorado ?? $tutorado->idCuenta;
    $examen = ExamenVocacional::where('idCuentaTutorado', $fieldKey)->first();
    
    if (!$examen) {
        return view('livewire.error-view')->with('error', 'Examen no encontrado');
    }

    return view('dashboard-examenv-resultados', compact('examen'));
})->name('examenv.resultados');



Route::get('/', function () {
    return view('welcome');
});

// Para desarrollo sin autenticación:
Route::get('/dashboardE', function () {
    return view('dashboard-inicio-estudiante');
})->name('dashboardE'); // Quita el middleware auth para pruebas

Route::get('/mi-informacionE', function () {
    return view('dashboard-mi-informacionE');
})->name('mi-informacionE'); // Quita el middleware auth para pruebas

Route::get('/mi-informacionEe', function () {
    return view('dashboard-mi-informacionE-editar');
})->name('mi-informacionEe');

Route::get('/examenv', function () {
    return view('dashboard-examenv');
})->name('examenv');

Route::get('/examenv-preguntas', function () {
    return view('dashboard-examenv-preguntas');
})->name('examenv.preguntas');


Route::get('/cuaderno-actividades', function () {
    return view('dashboard-cuaderno-actividades');
})->name('cuaderno.actividades');

Route::get('/analisisfodae', function () {
    return view('dashboard-analisis-foda');
})->name('analisisfodae');

Route::get('/comprensionlectorae', function () {
    return view('dashboard-comprension-lectora');
})->name('comprensionlectorae');

Route::get('/lineavida', function () {
    return view('dashboard-lineavida');
})->name('lineavida');

Route::get('/citas-tutorados', function () {
    return view('dashboard-citas-tutorados');
})->name('citas.tutorados');

//Ruta para el ejemplo de la tarjeta KEVIN
Route::get('/aviso-examen', function () {
    return view('aviso-examen-example');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/modal', function () {
    return view('components.modal');
});

// KuriZd rutas
Route::get('/lista-tutores-tutorados', function () {
    return view('tutor-tutoradosadmin-seleccion');
})->name('lista-tutores-tutorados');

Route::view('/admin-editar-perfil/{curp}', 'admin-editar-perfil')->name('admin-editar-perfil');

Route::get('/admin-home-page', function () {
    return view('admin-home-page');
})->name('admin-home-page');

Route::get('/admin-agregar-user', function () {
    return view('admin-agregar-user');
})->name('admin-agregar-user');

Route::get('/admin-citas', function () {
    return view('admin-citas-perfil');
})->name('admin-citas');


//RUTA PARA VER LA LISTA DE TUTORES Y TUTORADOS
//Route::view('lista-tutores-tutorados','lista-tutorados-tutores')->name('lista-tutores-tutorados');

Route::get('/test', function () {
    return 'Hola desde Livewire';
});


Route::get('/test', function () {
    return 'Hola desde Livewire';
});


Route::get('/test-component', function () {
    return view('test-component-view');
});




Route::prefix('/tutor')->name('tutor.')->group(function () {

    Route::get('/', function () {
        return view('tutor-index');
    })->name('index');

    Route::get('/edit-info/{curp}', function ($curp) {
        return view('tutor-edit-info', ['curp' => $curp]);
    })->name('info');

    Route::prefix('/asistencia')->name('asistencia.')->group(function () {
        Route::get('/', function () {
            return view('tutor-asistencia-seleccion');
        })->name('seleccion');
        Route::get('/grupo', function () {
            $grupoId = request('grupo');
            return view('tutor-asistencia-index', compact('grupoId'));
        })->name('index');
    });
    Route::prefix('/reportes')->name('reportes.')->group(function () {
        Route::get('/', function () {
            return view('tutor-reportes-seleccion');
        })->name('index');

        Route::get('/reporte/{grupoId}/{cicloId}', function ($grupoId, $cicloId) {
            return view('tutor-reporte-result', compact('grupoId', 'cicloId'));
        })->name('result');
    });

    Route::prefix('/tutorados')->name('tutorados.')->group(function () {
        Route::get('/', function () {
            return view('tutor-tutorados-seleccion');
        })->name('index');
        Route::get('/grupo', function () {
            $grupoId = request('grupo');
            return view('tutor-tutorados-result', compact('grupoId'));
        })->name('result');

        Route::prefix('/tutorado')->name('tutorado.')->group(function () {
            Route::get('/{idCuentaTutorado}', function ($idCuentaTutorado) {
                return view('tutor-tutorado-index', compact('idCuentaTutorado'));
            })->name('tutorado');

            Route::get('/necesidad/{idCuentaTutorado}', function ($idCuentaTutorado) {
                return view('tutor-tutorado-necesidad', compact('idCuentaTutorado'));
            })->name('necesidad');

            Route::get('/actividad', function () {
                return view('tutor-tutorado-index');
            })->name('result');
        });
    });

    Route::prefix('/actividades')->name('actividades.')->group(function () {
        Route::get('/', function () {
            return view('tutor-actividades-seleccion');
        })->name('index');

        Route::prefix('/banco')->name('banco.')->group(function () {
            Route::get('/', function () {
                $grupoId = request('grupoId');
                return view('tutor-actividades', compact('grupoId'));
            })->name('index');

            Route::get('/mostrar', function () {
                $grupoId = request('grupoId');
                $nAct = request('numeroActividad');
                return view('tutor-actividades-mostrar', compact('grupoId', 'nAct'));
            })->name('mostrar');



            Route::get('/comprension', function () {
                $idTutorado = request('idTutorado');
                $grupoId = request('grupoId');
                $nAct = request('numeroActividad');
                return view('tutor-actividades.comprension', compact('idTutorado', 'grupoId', 'nAct'));
            })->name('comprension');

            Route::get('/foda', function () {
                $idTutorado = request('idTutorado');
                $grupoId = request('grupoId');
                $nAct = request('numeroActividad');
                return view('tutor-actividades.foda', compact('idTutorado', 'grupoId', 'nAct'));
            })->name('foda');

            Route::get('/vida', function () {
                $idTutorado = request('idTutorado');
                $grupoId = request('grupoId');
                $nAct = request('numeroActividad');
                return view('tutor-actividades.vida', compact('idTutorado', 'grupoId', 'nAct'));
            })->name('vida');

            Route::get('/examen', function () {
                $idTutorado = request('idTutorado');
                $grupoId = request('grupoId');
                $nAct = request('numeroActividad');
                return view('tutor-examenv', compact('idTutorado', 'grupoId', 'nAct'));
            })->name('examen');
        });
    });
});
