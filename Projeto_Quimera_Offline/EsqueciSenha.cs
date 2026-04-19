using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Net.Mail;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using MySql.Data.MySqlClient;

namespace Projeto_integrador
{
    public partial class EsqueciSenha : Form
    {
        public EsqueciSenha()
        {
            InitializeComponent();
        }

        private void label1_Click(object sender, EventArgs e)
        {

        }
        string codigoGerado;
        private void button1_Click(object sender, EventArgs e)
        {



            Random rnd = new Random();
            codigoGerado = rnd.Next(100000, 999999).ToString();

            string corpoEmail = $"Olá!\n\n" +
                                $"Você solicitou um código de verificação para concluir seu cadastro.\n\n" +
                                $"Seu código de verificação é: {codigoGerado}\n\n" +
                                $"Por favor, insira este código no campo indicado para confirmar seu email.\n\n" +
                                $"Se você não solicitou este código, ignore este email.\n\n" +
                                $"Atenciosamente,\nEquipe Quimera Games";

            MailMessage mail = new MailMessage();
            mail.From = new MailAddress("quimeraggames@gmail.com");
            mail.To.Add(txtEmail.Text);
            mail.Subject = "QUIMERA GAMES";
            mail.Body = corpoEmail;

            SmtpClient smtp = new SmtpClient("smtp.gmail.com", 587);
            smtp.Credentials = new NetworkCredential("quimeraggames@gmail.com", "mhas gggt gaja aqez");
            smtp.EnableSsl = true;

            try
            {
                smtp.Send(mail);
                lblVerificar.Text = ("Código enviado para o email.");
            }
            catch (Exception ex)
            {
                lblVerificar.Text = ("Erro ao enviar email: " + ex.Message);
            }
        }

        private void button1_Click_1(object sender, EventArgs e)
        {

        }

        private void button1_Click_2(object sender, EventArgs e)
        {
            if (txtCodigo.Text == codigoGerado)
            {
                lblCodigo.Text = ("Email verificado com sucesso!");

                label1.Visible = false;
                lblVerificar.Visible = false;
                lblCodigo.Visible = false;
                EnviarCodigo.Visible = false;
                button1.Visible = false;
                txtCodigo.Visible = false;

                txtSenha.Visible = true;
                btnConfirma.Visible = true;
                txtConfirma.Visible = true;
                label4.Visible = true;
                label3.Text = ("NOVA SENHA");
                verSenha.Visible = true;
            }
            else
            {
                lblCodigo.Text = ("Código incorreto. Verifique seu email.");

            }
        }

        private void txtSenha_TextChanged(object sender, EventArgs e)
        {

        }

        private void btnConfirma_Click(object sender, EventArgs e)
        {

            string novaSenha = txtSenha.Text;
            string confirmarSenha = txtConfirma.Text;
            string emailUsuario = txtEmail.Text;

            if (novaSenha != confirmarSenha)
            {
                lblMensagem.Text = "As senhas não coincidem!";
                lblMensagem.ForeColor = Color.Red;
                return;
            }

            try
            {
                Conexao conexao = new Conexao();

                using (MySqlConnection conn = conexao.GetConnection()) // usando sua classe
                {
                    conn.Open();
                    string query = "UPDATE cadastro SET senha = @senha WHERE email = @email";
                    MySqlCommand cmd = new MySqlCommand(query, conn);
                    cmd.Parameters.AddWithValue("@senha", novaSenha); // idealmente, criptografar
                    cmd.Parameters.AddWithValue("@email", emailUsuario);

                    int linhasAfetadas = cmd.ExecuteNonQuery();

                    if (linhasAfetadas > 0)
                    {
                        lblMensagem.Text = "Senha atualizada com sucesso!";
                        lblMensagem.ForeColor = Color.Green;




                        this.Hide();
                        TelaLogin login = new TelaLogin();
                        login.Show();


                    }
                    else
                    {
                        lblMensagem.Text = "Usuário não encontrado!";
                        lblMensagem.ForeColor = Color.Red;
                    }
                }
            }
            catch (Exception ex)
            {
                lblMensagem.Text = "Erro: " + ex.Message;
                lblMensagem.ForeColor = Color.Red;
            }

        }


        private void verSenha_CheckedChanged(object sender, EventArgs e)
        {
            if (verSenha.TabStop == true)
            {
                if (txtSenha.PasswordChar == '*')
                {
                    txtSenha.PasswordChar = '\0'; // Mostra a senha
                }
                else
                {
                    txtSenha.PasswordChar = '*'; // Oculta a senha
                }
            }
        }

        private void checkBox1_CheckedChanged(object sender, EventArgs e)
        {
            if (checkBox1.TabStop == true)
            {
                if (txtConfirma.PasswordChar == '*')
                {
                    txtConfirma.PasswordChar = '\0'; // Mostra a senha
                }
                else
                {
                    txtConfirma.PasswordChar = '*'; // Oculta a senha
                }
            }
        }

        private void EsqueciSenha_Load(object sender, EventArgs e)
        {

        }
    }
}

