using Microsoft.VisualBasic.Logging;
using MySql.Data.MySqlClient;
using System;
using System.Collections;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using static System.Windows.Forms.VisualStyles.VisualStyleElement.Button;

namespace Projeto_integrador
{
    public partial class TelaLogin : Form
    {
        public string EmailOuUsuario { get; private set; }
        public string TipoUsuario { get; private set; }

        public TelaLogin()
        {
            InitializeComponent();
        }


        private void TelaLogin_Load(object sender, EventArgs e)
        {

        }


        private void button1_Click(object sender, EventArgs e)
        {
            string login = textEmail.Text.Trim();
            string senha = textSenha.Text.Trim();

            if (string.IsNullOrEmpty(login) || string.IsNullOrEmpty(senha))
            {
                MessageBox.Show("Preencha todos os campos!");
                return;
            }

            string connectionString = "server=10.37.44.72;user id=root;password=root;database=projeto_quimera";

            using (MySqlConnection conn = new MySqlConnection(connectionString))
            {
                try
                {
                    conn.Open();
                    string query = "SELECT tipo_user FROM cadastro WHERE (email = @login OR nome_user = @login) AND senha = @senha LIMIT 1";
                    MySqlCommand cmd = new MySqlCommand(query, conn);
                    cmd.Parameters.AddWithValue("@login", login);
                    cmd.Parameters.AddWithValue("@senha", senha);

                    var tipo = cmd.ExecuteScalar();

                    if (tipo != null)
                    {
                        EmailOuUsuario = login;
                        TipoUsuario = tipo.ToString(); // "adm" ou "comum"

                        // Salva informações na sessão global
                        Sessao.EmailOuUsuario = EmailOuUsuario;
                        Sessao.TipoUsuario = TipoUsuario;

                        DialogResult = DialogResult.OK;
                        Close();
                    }
                    else
                    {
                        

                        MessageBox.Show("Email/usuário ou senha incorretos.");
                        esqueciSenha.Visible = true;
                    }



                }
                catch (Exception ex)
                {
                    MessageBox.Show("Erro ao fazer login: " + ex.Message);
                }


                

            }
        }


        private void textSenha_TextChanged(object sender, EventArgs e)
        {

        }

        private void button2_Click(object sender, EventArgs e)
        {
            TelaCadastroLogin tela = new TelaCadastroLogin();
            tela.ShowDialog();
            this.Hide();
        }

        private void radioButton1_CheckedChanged(object sender, EventArgs e)
        {

        }

        private void checkBox1_CheckedChanged(object sender, EventArgs e)
        {

            if (verSenha.TabStop == true)
            {
                if (textSenha.PasswordChar == '*')
                {
                    textSenha.PasswordChar = '\0'; // Mostra a senha
                }
                else
                {
                    textSenha.PasswordChar = '*'; // Oculta a senha
                }
            }


        }

        private void esqueciSenha_Click(object sender, EventArgs e)
        {

            EsqueciSenha novoForm = new EsqueciSenha(); // substitua pelo nome do seu Form
            novoForm.Show();

        }
    }
}
