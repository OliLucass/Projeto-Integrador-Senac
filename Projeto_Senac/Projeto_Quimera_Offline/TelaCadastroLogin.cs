using Microsoft.VisualBasic.ApplicationServices;
using MySql.Data.MySqlClient;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Security.Cryptography.X509Certificates;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Projeto_integrador
{
    public partial class TelaCadastroLogin : Form
    {

        private Buscas busca = new Buscas();

        public TelaCadastroLogin()
        {
            InitializeComponent();
            busca.teste();
        }

       

        

        private void TelaCadastroLogin_Load(object sender, EventArgs e)
        {
            tipo_user.Items.Clear(); // limpa qualquer valor anterior

            if (Sessao.TipoUsuario == "adm")
            {
                // Administrador pode escolher o tipo do novo usuário
                tipo_user.Items.Add("adm");
                tipo_user.Items.Add("comum");
                tipo_user.SelectedIndex = 0;

                tipo_user.Visible = true;
                tipo_user1.Visible = true;
            }
            else
            {
                // Usuário comum ou não logado só pode criar contas comuns
                tipo_user.Items.Add("comum");
                tipo_user.SelectedIndex = 0;

                tipo_user.Visible = false;
                tipo_user1.Visible = false;
            }
        }

        private void label8_Click(object sender, EventArgs e)
        {

        }

       /* private void url_foto_Click(object sender, EventArgs e)
        {
            OpenFileDialog x = new OpenFileDialog();
            x.Filter = "Arquivos de Imagem|*.jpg;*.jpeg;*";

            if (x.ShowDialog() == DialogResult.OK)
            {
                // Exibe a imagem escolhida em um PictureBox
                pictureBox1.Image = Image.FromFile(x.FileName);
            }
        }
       */

        //jaca preta

        private void button1_Click(object sender, EventArgs e)
        {


            Conexao conexao = new Conexao();
            Buscas busca = new Buscas();

           
            {
                DadosBanco.Email = email.Text.Trim();
                DadosBanco.Nome = nome.Text.Trim();
                DadosBanco.Nick = nome_user.Text.Trim();
                DadosBanco.CPF = cpf.Text.Trim();
                DadosBanco.Tipo = tipo_user.Text.Trim();
                DadosBanco.Senha = senha.Text.Trim();
                DadosBanco.Senha2 = confsenha.Text.Trim();
            };

            string varemail = email.Text.Trim();
            string varnome = nome.Text.Trim();
            string varnick = nome_user.Text.Trim();
            string varcpf = cpf.Text.Trim();
            string vartipouser = tipo_user.Text.Trim();
            string varsenha = senha.Text.Trim();
            string varsenha2 = confsenha.Text.Trim();

            if (Sessao.TipoUsuario == "comum")
            {
                vartipouser = "comum";
            }
            else if (string.IsNullOrEmpty(vartipouser))
            {
                vartipouser = "comum";
            }

            // Verifica se e-mail já existe
            if (busca.busca_email(varemail))
            {
                MessageBox.Show("Este e-mail já está cadastrado!");
                return; // interrompe o cadastro
            }

            if (varcpf.Any(char.IsLetter))
            {
                MessageBox.Show("Use apenas números no cpf!!");
                return;
            }

            if (busca.busca_cpf(varcpf))
            {
                MessageBox.Show("Este cpf já está cadastrado em uso!!!");
                return;
            }

            // Verifica se senhas conferem
            if (varsenha != varsenha2)
            {
                MessageBox.Show("As senhas não conferem!");
                return;
            }

            if (varsenha.Length < 8)
            {
                MessageBox.Show("A senha deve ter pelo menos 8 caracteres.");
                return;
            }
            // 2 - Pelo menos 1 número
            else if (!varsenha.Any(char.IsDigit))
            {
                MessageBox.Show("A senha deve conter pelo menos 1 número.");
                return;
            }
            // 3 - Pelo menos 1 letra maiúscula
            else if (!varsenha.Any(char.IsUpper))
            {
                MessageBox.Show("A senha deve conter pelo menos 1 letra maiúscula.");
                return;
            }
            // pelo memnos 1 letra menoscola
            else if (!varsenha.Any(char.IsLower))
            {
                MessageBox.Show("A senha deve conter pelo menos 1 letra minuscula.");
                return;
            }
            // 4 - Pelo menos 1 caractere especial
            else if (!varsenha.Any(ch => !char.IsLetterOrDigit(ch)))
            {
                MessageBox.Show("A senha deve conter pelo menos 1 caractere especial.");
                return;
            }

            // Aqui você insere o usuário no banco
            

            MessageBox.Show("Você precisa verificar o seu email.");

            ValidacaoEmail novo = new ValidacaoEmail();
            novo.ShowDialog();
            this.Hide();
            
        }
        


        private void tipo_user_TextChanged(object sender, EventArgs e)
        {

        }

        private void email_Leave(object sender, EventArgs e)
        {

            string emailDigitado = email.Text.Trim();
            string padrao = @"^[^@\s]+@[^@\s]+\.[^@\s]+$";

            if (!Regex.IsMatch(emailDigitado, padrao))
            {
                lblMensagem.Text = "Formato de e-mail inválido!";
                lblMensagem.ForeColor = Color.Red;
            }
            else
            {
                lblMensagem.Text = "";
            }

        }

        private void email_TextChanged(object sender, EventArgs e)
        {

        }

        private void cpf_TextChanged(object sender, EventArgs e)
        {

        }

        private void cpf_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (!char.IsControl(e.KeyChar) && !char.IsDigit(e.KeyChar))
            {
                // Block any non-numeric character (like letters, punctuation, etc.)
                e.Handled = true;
            }

            cpf.MaxLength = 11;

        }

        private void tipo_user_SelectedIndexChanged(object sender, EventArgs e)
        {

        }
    }
}
