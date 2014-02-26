<?php

//Iconos:
//http://getbootstrap.com/2.3.2/base-css.html#icons

class Menu {

	private function items() {
		return array(
			'Inicio' => array(
				'icono' => 'icon-home',
				'controller' => 'inicio'
			),
			'Proyectos' => array(
				'icono' => 'icon-tag',
				'controller' => 'proyectos'
			),
			'Modelos' => array(
				'icono' => 'icon-tags',
				'controller' => 'modelos'
			),
			'Cotizaciones' => array(
				'icono' => 'icon-tasks',
				'controller' => 'cotizaciones'
			),
			'Configuración' => array(
				'icono' => 'icon-cog',
				'submenu' => array(

					'Usuarios' => array(
						'icono' => 'icon-user',
						'controller' => 'usuarios'
					),
					'Archivos' => array(
						'icono' => 'icon-picture',
						'controller' => 'media'
					),
					'Comunas' => array(
						'icono' => 'icon-map-marker',
						'controller' => 'comunas'
					),
					'Provincias' => array(
						'icono' => 'icon-map-marker',
						'controller' => 'provincias'
					),
					'Regiones' => array(
						'icono' => 'icon-map-marker',
						'controller' => 'regiones'
					)
				)
			)
		);
	}

	/**
	 * getModulos
	 * Retorna un arreglo de primer nivel para exponer
	 * los modulos disponible para cada usuario como permiso.
	 * Se utiliza en /usuarios
	 * 
	 * @return
	 */
	public function getModulos() {
		$output = array();
		$items = $this->items();

		foreach($items as $i => $item) {
			if(isset($item['controller'])) {
				$output[$item['controller']] = $i;
			} elseif(isset($item['submenu'])) {
				foreach($item['submenu'] as $k => $submenu) {
					$output[$submenu['controller']] = $k;
				}
			}
		}

		return $output;
	}

	/**
	 * getItems
	 * Retorna un arreglo para imprimir los elementos en la vista
	 * ademas detecta si el usuario se encuentra en el controller seleccionado
	 * 
	 * @return
	 */
	public function getItems() {
		$CI =& get_instance();
		$CI->load->model('login_model');
		$permisos = $CI->login_model->getModulosPorUsuario($CI->session->userdata('id'));

		$output = array();
		$items = $this->items();
		foreach($items as $i => $item) {
			if(isset($item['controller']) && in_array($item['controller'], $permisos)) {
				$output[$i] = $items[$i];

				if(strstr($_SERVER["REQUEST_URI"], 'php/'.$item['controller'])) {
					$output[$i]['active'] = true;
				}

			} elseif(isset($item['submenu'])) {
				foreach($item['submenu'] as $k => $submenu) {
					if(in_array($submenu['controller'],$permisos)) {
						$output[$i]['icono'] = $items[$i]['icono'];
						$output[$i]['submenu'][$k] = $items[$i]['submenu'][$k];

						if(strstr($_SERVER["REQUEST_URI"], 'php/'.$submenu['controller'])) {
							$output[$i]['submenu'][$k]['active'] = true;
						}
					}
				}
			}
		}
		return $output;
	}

}