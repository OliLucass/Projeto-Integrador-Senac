using MySql.Data.MySqlClient;
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
    public partial class ListaJogos : Form
    {

        private Buscas busca = new Buscas();

        public ListaJogos()
        {
            InitializeComponent();
        }

        private void ListaJogos_Load(object sender, EventArgs e)
        {
            CarregarJogos();

            dgv.Columns["id_play"].Visible = false;
            dgv.Columns["Imagens_jogos"].Visible = false;
            dgv.Columns["Imagens_cen1"].Visible = false;
            dgv.Columns["Imagens_cen2"].Visible = false;
        }

        // id_play, titulo, desenvolvedora, distribuidora, informacoes, data_lancamento, req_sistema, Imagens_jogos, Imagens_cen1, Imagens_cen2

        private void CarregarJogos()
        {
            Conexao conexao = new Conexao();
            string query = @"SELECT id_play, titulo, desenvolvedora, distribuidora, informacoes, data_lancamento, req_sistema, Imagens_jogos, Imagens_cen1, Imagens_cen2 FROM jogos";

            // pega uma nova conexãoasdasd
            using (MySqlConnection con = conexao.GetConnection())
            {
                try
                {
                    con.Open();

                    MySqlCommand cmd = new MySqlCommand(query, con);
                    MySqlDataAdapter da = new MySqlDataAdapter(cmd);
                    DataTable dt = new DataTable();
                    da.Fill(dt);

                    dgv.DataSource = dt;
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Erro ao carregar jogos: " + ex.Message);
                }
            }
        }

        private void dataGridView1_CellContentClick(object sender, DataGridViewCellEventArgs e)
        {

        }

        private void b1_Click(object sender, EventArgs e)
        {

            string cb = cb1.Text;
            string tb = tb1.Text;

            if (cb == "Titulo")
            {
                dgv.DataSource = busca.procura_titulo(tb);

            }

            if (cb == "Desenvolvedora")
            {
                dgv.DataSource = busca.procura_desenvolvedora(tb);
            }

            if (cb == "Distribuidora")
            {
                dgv.DataSource = busca.procura_distribuidora(tb);
            }

            if (cb == "Informacoes")
            {
                dgv.DataSource = busca.procura_informacoes(tb);
            }
        }

        private void label3_Click(object sender, EventArgs e)
        {

        }

        private void dgv_CellClick(object sender, DataGridViewCellEventArgs e)
        {
            if (e.RowIndex >= 0) // garante que clicou em uma linha válida
            {

                DataGridViewRow linha = dgv.Rows[e.RowIndex];

                lb2.Visible = true;
                string nome = linha.Cells["titulo"].Value?.ToString();
                lb2.Text = nome;

                // pega as URLs direto das colunas do DataGridView
                string urlImagemPrincipal = linha.Cells["Imagens_jogos"].Value?.ToString();
                string urlCenario1 = linha.Cells["Imagens_cen1"].Value?.ToString();
                string urlCenario2 = linha.Cells["Imagens_cen2"].Value?.ToString();

                // carrega as imagens nos PictureBox
                if (!string.IsNullOrEmpty(urlImagemPrincipal))
                {
                    pictureBox1.ImageLocation = urlImagemPrincipal;
                    pictureBox1.SizeMode = PictureBoxSizeMode.Zoom;
                }
                else
                {
                    pictureBox1.Image = null;
                }

                if (!string.IsNullOrEmpty(urlCenario1))
                {
                    pictureBox2.ImageLocation = urlCenario1;
                    pictureBox2.SizeMode = PictureBoxSizeMode.Zoom;
                }
                else
                {
                    pictureBox2.Image = null;
                }

                if (!string.IsNullOrEmpty(urlCenario2))
                {
                    pictureBox3.ImageLocation = urlCenario2;
                    pictureBox3.SizeMode = PictureBoxSizeMode.Zoom;
                }
                else
                {
                    pictureBox3.Image = null;
                }
            }

        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {

        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        private void pictureBox3_Click(object sender, EventArgs e)
        {

        }

        private void cb1_SelectedIndexChanged(object sender, EventArgs e)
        {

        }
    }
}
