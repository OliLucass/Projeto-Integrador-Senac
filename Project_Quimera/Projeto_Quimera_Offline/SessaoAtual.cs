using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Projeto_integrador
{
    public static class SessaoAtual
    {
        private static string connectionString = "server=10.37.44.72;user id=root;password=root;database=projeto_quimera";
        public static string UsuarioLogado { get; private set; } = null;
        public static string TipoUsuario { get; private set; } = null;

        /// ALTERAÇÃO: recebe Menu em vez de Form /
        public static bool VerificarLogin(Menu menu)
        {
            if (!string.IsNullOrEmpty(UsuarioLogado))
            {
                AplicarPermissoes(menu);
                return true;
            }

            using (TelaLogin login = new TelaLogin())
            {
                if (login.ShowDialog() == DialogResult.OK)
                {
                    UsuarioLogado = login.EmailOuUsuario;
                    TipoUsuario = login.TipoUsuario;
                    AplicarPermissoes(menu);
                    return true;
                }
            }

            return false;
        }

        // ALTERAÇÃO: recebe Menu em vez de Form
        private static void AplicarPermissoes(Menu menu)
        {
            // Normaliza e verifica 'adm'
            string tipo = (TipoUsuario ?? "").Trim().ToLower();
            bool ehAdmin = tipo == "adm";

            // chama método público do Menu (sem cast)
            menu.AplicarPermissoesLocais(ehAdmin);
        }

        public static void Logout()
        {
            UsuarioLogado = null;
            TipoUsuario = null;
        }
    }
}
