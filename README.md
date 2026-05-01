# 📝 TaskManager - Sistema de Gestión de Tareas Profesional

¡Bienvenido! Este es un sistema integral de gestión de tareas desarrollado con **Laravel 12** y **Bootstrap 5**. El proyecto ha sido diseñado bajo estándares profesionales de programación, aplicando el principio de responsabilidad única y una interfaz de usuario limpia y adaptativa.

## 🚀 Características principales
*   **Gestión de Tareas (CRUD):** Organización dinámica de pendientes con asignación de prioridades.
*   **Dashboard Visual:** Interfaz basada en columnas de prioridad (Urgente, Prioritario, Normal, Baja) para una rápida toma de decisiones.
*   **Sistema de Autenticación:** Registro y Login seguro con manejo de sesiones y protección CSRF.
*   **Gestión de Perfil:** El usuario puede actualizar su nombre, correo electrónico y subir una foto de perfil personalizada.
*   **Seguridad Avanzada:** Módulo dedicado para el cambio de contraseña con validación de la credencial actual y encriptación Hash.

## 🛠️ Stack Tecnológico
*   **Backend:** PHP 8.2+ & Laravel 12.
*   **Frontend:** Blade Templates, Bootstrap 5, Bootstrap Icons.
*   **Base de Datos:** MySQL / SQLite.
*   **Arquitectura:** Controladores separados para Auth y Perfil (Clean Code).

## 📦 Instalación y Configuración Local

Sigue estos pasos para ejecutar el proyecto en tu entorno local:

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/TU_USUARIO/sistema-gestion-tareas.git](https://github.com/TU_USUARIO/sistema-gestion-tareas.git)
   cd sistema-gestion-tareas
2. Instalar dependencias de PHP y JavaScript:

Bash
composer install
npm install && npm run build

3. Configurar el entorno:
Copia el archivo de ejemplo y configura tus credenciales de base de datos en el archivo .env:

Bash
cp .env.example .env

4. Generar la clave de aplicación:
Bash
php artisan key:generate

5. Migrar base de datos y cargar Categorías (Seeders):
Importante: Este paso crea las tablas y las categorías necesarias (Urgente, Baja, etc.) para que el sistema funcione correctamente.

Bash
php artisan migrate --seed

6. Crear el enlace simbólico para las imágenes:
Bash
php artisan storage:link

7. Iniciar el servidor:
Bash
php artisan serve

Desarrollado con dedicación por [Eduardo Quevedo Victoria] 🚀
