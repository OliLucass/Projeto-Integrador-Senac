using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Projeto_integrador
{
    public partial class Menu : Form
    {
        public Menu()
        {
            InitializeComponent();
        }

        public void cadastroToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!SessaoAtual.VerificarLogin(this)) return;


            if (SessaoAtual.TipoUsuario != "adm")
            {
                MessageBox.Show("Acesso permitido apenas para administradores.");
                return;
            }

            FecharFilhosAbertos();
            if (this.MdiChildren.Length == 0)
            {


                TelaCadastroLogin tela = new TelaCadastroLogin();
                tela.MdiParent = this;
                tela.Show();
            }
            else
            {

                this.MdiChildren[0].Activate();
            }

        }

        public void loginToolStripMenuItem_Click(object sender, EventArgs e)
        {
            SessaoAtual.VerificarLogin(this);

        }

        public void cadastroJogoToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!SessaoAtual.VerificarLogin(this)) return;


            if (SessaoAtual.TipoUsuario != "adm")
            {

                MessageBox.Show("Acesso permitido apenas para administradores.");
                return;
            }

            FecharFilhosAbertos();
            if (this.MdiChildren.Length == 0)
            {


                CadastroJogos tela = new CadastroJogos();
                tela.MdiParent = this;
                tela.Show();
            }
            else
            {

                this.MdiChildren[0].Activate();
            }


        }

        public void listaJogosToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!SessaoAtual.VerificarLogin(this)) return;

            FecharFilhosAbertos();

            if (this.MdiChildren.Length == 0)
            {


                ListaJogos tela = new ListaJogos();
                tela.MdiParent = this;
                tela.Show();
            }
            else
            {

                this.MdiChildren[0].Activate();
            }


        }

        public void sorteadorToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!SessaoAtual.VerificarLogin(this)) return;



            FecharFilhosAbertos();

            if (this.MdiChildren.Length == 0)
            {


                Sorteador tela = new Sorteador();
                tela.MdiParent = this;
                tela.Show();
            }
            else
            {

                this.MdiChildren[0].Activate();
            }


        }

        private void FecharFilhosAbertos()
        {
            foreach (Form form in this.MdiChildren)
            {
                form.Close();
            }
        }

        public void AplicarPermissoesLocais(bool ehAdmin)
        {
            cadastroToolStripMenuItem.Enabled = ehAdmin;
            cadastroJogoToolStripMenuItem.Enabled = ehAdmin;
            listaJogosToolStripMenuItem.Enabled = true;
            sorteadorToolStripMenuItem.Enabled = true;
        }

        public void Menu_Load(object sender, EventArgs e)
        {
            cadastroToolStripMenuItem.Enabled = false;
            cadastroJogoToolStripMenuItem.Enabled = false;
            listaJogosToolStripMenuItem.Enabled = false;
            sorteadorToolStripMenuItem.Enabled = false;
        }

        private void logoutToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Application.Restart();
        }

        private void tabelasToolStripMenuItem_Click(object sender, EventArgs e)
        {
        }

        private void videotesteToolStripMenuItem_Click(object sender, EventArgs e)
        {
        }
    }
}
