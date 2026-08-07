<?php
/**
 * DashboardController
 * Controlador del panel principal, accesible tanto para
 * Super Administrador como para Cliente (con datos diferenciados).
 *
 * Ruta: app/controllers/DashboardController.php
 */

class DashboardController extends Controller
{
    /**
     * GET /dashboard
     */
    public function index(): void
    {
        // Exige sesion activa; ambos roles pueden acceder al dashboard
        AuthMiddleware::handle([ROLE_SUPERADMIN, ROLE_CLIENTE]);

        $data = [
            'titulo' => 'Panel de Control',
        ];

        if (AuthHelper::esSuperAdmin()) {
            $clienteModel = $this->model('ClienteModel');

            $data['esSuperAdmin']       = true;
            $data['totalClientes']      = $clienteModel->total();
            $data['clientesActivos']    = $clienteModel->totalActivos();
            $data['clientesPorVencer']  = $clienteModel->totalPorVencer(7);
            $data['clientesRecientes']  = $clienteModel->listarRecientes(5);
        } else {
            $data['esSuperAdmin'] = false;
        }

        $this->view('dashboard/index', $data);
    }
}
