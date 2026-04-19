using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using MySql.Data.MySqlClient;
using static System.Net.Mime.MediaTypeNames;

namespace Projeto_integrador
{
    public partial class CadastroJogos : Form
    {
        private int jogoSelecionadoId = -1;


        public CadastroJogos()
        {
            InitializeComponent();
            this.dgvJogos.CellClick += new DataGridViewCellEventHandler(this.dgvJogos_CellClick);
            CarregarCategorias();
            AtualizarGrid();
        }

        private void CarregarCategorias()
        {
            try
            {
                Conexao conexao = new Conexao();
                using (var conn = conexao.GetConnection())
                {
                    string sql = "SELECT id_categoria, tipo_categoria FROM categorias";
                    MySqlDataAdapter da = new MySqlDataAdapter(sql, conn);
                    DataTable dt = new DataTable();
                    da.Fill(dt);

                    cmbCategoria.DisplayMember = "tipo_categoria"; // O que aparece
                    cmbCategoria.ValueMember = "id_categoria";     // O que fica guardado
                    cmbCategoria.DataSource = dt;
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Erro ao carregar categorias: " + ex.Message);
            }
        }

        private void btnCadastrar_Click(object sender, EventArgs e)
        {

            if (cmbCategoria.SelectedValue == null)
            {
                MessageBox.Show("Por favor, selecione uma categoria.");
                return;
            }

            string titulo = txtTitulo.Text.Trim();
            string desenvolvedora = txtDesenvolvedora.Text.Trim();
            string distribuidora = txtDistribuidora.Text.Trim();
            string informacoes = txtInformacoes.Text.Trim();
            string reqSistema = txtReq_Sis.Text.Trim();
            string imagem1 = txtImagem1.Text.Trim();
            string imagem2 = txtImagem2.Text.Trim();
            string imagem3 = txtImagem3.Text.Trim();
            string valorTexto = txtValor.Text.Trim();
            DateTime dataLancamento = dtpDataLancamento.Value;

            if (string.IsNullOrWhiteSpace(titulo) ||
                 string.IsNullOrWhiteSpace(desenvolvedora) ||
                 string.IsNullOrWhiteSpace(distribuidora) ||
                 string.IsNullOrWhiteSpace(informacoes) ||
                 string.IsNullOrWhiteSpace(reqSistema) ||
                 string.IsNullOrWhiteSpace(imagem1) ||
                 string.IsNullOrWhiteSpace(imagem2) ||
                 string.IsNullOrWhiteSpace(imagem3) ||
                 string.IsNullOrWhiteSpace(valorTexto))

            {
                MessageBox.Show("Por favor, preencha todos os campos antes de cadastrar o jogo.");
                return;
            }

            if (!decimal.TryParse(valorTexto, out decimal valor))
            {
                MessageBox.Show("Valor inválido. Digite apenas números e vírgula.");
                return;
            }

            int categoriaId = Convert.ToInt32(cmbCategoria.SelectedValue);


            Jogo jogo = new Jogo
            {
                Categoria = categoriaId.ToString(),
                Titulo = titulo,
                Desenvolvedora = desenvolvedora,
                Distribuidora = distribuidora,
                Informacoes = informacoes,
                DataLancamento = dataLancamento,
                RequisitosSistema = reqSistema,
                Valor = valor,
                ImagemCapa = imagem1,
                ImagemCen1 = imagem2,
                ImagemCen2 = imagem3,
            };


            try
            {
                if (jogoSelecionadoId == -1)
                {
                    // Novo cadastro
                    jogo.Inserir();
                    MessageBox.Show("Jogo cadastrado com sucesso!");
                    LimparCampos();
                }
                else
                {
                    MessageBox.Show("Selecione 'Atualizar' para modificar o jogo.");
                }


                AtualizarGrid();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Erro ao cadastrar ou atualizar jogo: " + ex.Message);
            }

        }

        private void AtualizarGrid()
        {
            dgvJogos.DataSource = null;
            dgvJogos.DataSource = Jogo.ListarTodos();
        }

        private void btnAtualizar_Click(object sender, EventArgs e)
        {
            if (jogoSelecionadoId == -1)
            {
                MessageBox.Show("Por favor, selecione um jogo na lista para atualizar.");
                return;
            }

            if (cmbCategoria.SelectedValue == null)
            {
                MessageBox.Show("Por favor, selecione uma categoria.");
                return;
            }

            decimal valor;
            if (!decimal.TryParse(txtValor.Text, out valor))
            {
                MessageBox.Show("Valor inválido.");
                return;
            }

            int categoriaId = Convert.ToInt32(cmbCategoria.SelectedValue);

            Jogo jogo = new Jogo
            {
                Id = jogoSelecionadoId,
                Categoria = categoriaId.ToString(),
                Titulo = txtTitulo.Text,
                Desenvolvedora = txtDesenvolvedora.Text,
                Distribuidora = txtDistribuidora.Text,
                Informacoes = txtInformacoes.Text,
                DataLancamento = dtpDataLancamento.Value,
                RequisitosSistema = txtReq_Sis.Text,
                Valor = valor,
                ImagemCapa = txtImagem1.Text.Trim(),
                ImagemCen1 = txtImagem2.Text.Trim(),
                ImagemCen2 = txtImagem3.Text.Trim()
            };


            try
            {
                jogo.Atualizar();
                MessageBox.Show("Jogo atualizado com sucesso!");
                LimparCampos();
                jogoSelecionadoId = -1;
                AtualizarGrid();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Erro ao atualizar o jogo: " + ex.Message);
            }
        }


        private void dgvJogos_CellClick(object sender, DataGridViewCellEventArgs e)
        {
            if (e.RowIndex >= 0)
            {
                // Exibe mensagem de confirmação antes de preencher os campos
                var confirmar = MessageBox.Show(
                    "Deseja realmente selecionar este jogo para edição?",
                    "Confirmar Seleção",
                    MessageBoxButtons.YesNo,
                    MessageBoxIcon.Question);

                // Se o usuário clicar em "Não", nada é feito
                if (confirmar == DialogResult.No)
                {
                    return;
                }

                // Se confirmou, preenche os campos normalmente
                DataGridViewRow row = dgvJogos.Rows[e.RowIndex];
                jogoSelecionadoId = Convert.ToInt32(row.Cells["id_play"].Value);
                int idCategoria = Convert.ToInt32(row.Cells["id_categoria"].Value);
                cmbCategoria.SelectedValue = idCategoria;

                txtTitulo.Text = row.Cells["titulo"].Value.ToString();
                txtDesenvolvedora.Text = row.Cells["desenvolvedora"].Value.ToString();
                txtDistribuidora.Text = row.Cells["distribuidora"].Value.ToString();
                txtInformacoes.Text = row.Cells["informacoes"].Value.ToString();
                dtpDataLancamento.Value = Convert.ToDateTime(row.Cells["data_lancamento"].Value);
                txtReq_Sis.Text = row.Cells["req_sistema"].Value.ToString();

                if (row.Cells["valor"].Value != null && decimal.TryParse(row.Cells["valor"].Value.ToString(), out decimal valor))
                {
                    txtValor.Text = valor.ToString("F2"); // Formata com duas casas decimais
                }
                else
                {
                    txtValor.Text = "";
                }

                txtImagem1.Text = row.Cells["Imagens_jogos"].Value?.ToString() ?? "";
                txtImagem2.Text = row.Cells["Imagens_cen1"].Value?.ToString() ?? "";
                txtImagem3.Text = row.Cells["Imagens_cen2"].Value?.ToString() ?? "";
            }
        }





        private void LimparCampos()
        {
            cmbCategoria.SelectedIndex = -1;
            txtTitulo.Clear();
            txtDesenvolvedora.Clear();
            txtDistribuidora.Clear();
            txtInformacoes.Clear();
            txtReq_Sis.Clear();
            dtpDataLancamento.Value = DateTime.Now;
            txtImagem1.Clear();
            txtImagem2.Clear();
            txtImagem3.Clear();
        }

        private void CadastroJogos_Load(object sender, EventArgs e)
        {

        }


        private void btnRemover_Click(object sender, EventArgs e)
        {
            if (jogoSelecionadoId == -1)
            {
                MessageBox.Show("Por favor, selecione um jogo na lista para remover.");
                return;
            }

            var confirmResult = MessageBox.Show("Tem certeza que deseja remover este jogo?",
                                                 "Confirmar Remoção",
                                                 MessageBoxButtons.YesNo,
                                                 MessageBoxIcon.Warning);
            if (confirmResult == DialogResult.Yes)
            {
                try
                {
                    Jogo jogo = new Jogo { Id = jogoSelecionadoId };
                    jogo.Remover();
                    MessageBox.Show("Jogo removido com sucesso!");
                    LimparCampos();
                    jogoSelecionadoId = -1;
                    AtualizarGrid();
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Erro ao remover o jogo: " + ex.Message);
                }
            }
        }

        private void txtValor_TextChanged(object sender, EventArgs e)
        {

        }
    }
}
