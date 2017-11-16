<?php
/**
 * Este fichero define el contenido de la meta box de títulos.
 *
 * Como se trata de una meta box, esta plantilla únicamente se usa cuando se
 * está editando una entrada concreta. La plantilla supone que las siguientes
 * variables están definidas:
 * * $val {string} El valor de la extensión de título de la entrada actual.
 *
 * @author Bexandy Rodríguez <bexandy@gmail.com>
 * @link http://www.bexandy.com.ve
 * @since 1.0.0
 *
 * @package wprincipiante-ejemplo
 */

?>
  <label for="wprincipiante-extension-titulo">Texto:</label>
  <input type="text" name="wprincipiante-extension-titulo" value="<?php echo esc_attr($val); ?>" >
