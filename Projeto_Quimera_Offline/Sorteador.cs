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
using System.Net;
using ManagedDoom;
using static Projeto_integrador.RepositorioJogos;
using System.Diagnostics;

namespace Projeto_integrador
{
    public partial class Sorteador : Form
    {
        private Dictionary<string, Image> _cacheImagens = new Dictionary<string, Image>();

        private RepositorioJogos _repositorio;
        private string modo = "";
        private Dictionary<string, int> categorias;

        private List<string> _titulosAnimacao;
        private int _animIndex;
        private int _velocidade;
        private RepositorioJogos.Jogo _jogoSorteado;

        public Sorteador()
        {
            InitializeComponent();
            _repositorio = new RepositorioJogos();

            btn_jogar.Visible = false;

            categorias = new Dictionary<string, int>()
            {
                { "Todas", 0 },
                { "Ação", 1 },
                { "Aventura", 2 },
                { "Corrida", 3 },
                { "Estratégia", 4 },
                { "Esporte", 5 },
                { "FPS", 6 },
                { "Luta", 7 },
                { "Terror", 8 },
                { "Sobrevivência", 9 },
                { "RPG", 10 }
            };

            grp_resultado.Visible = false;
            txt_user.Visible = false;

            cb_cate.DataSource = new BindingSource(categorias, null);
            cb_cate.DisplayMember = "Key";
            cb_cate.ValueMember = "Value";
            cb_cate.SelectedIndex = -1;

            PreencherCategorias();
            timer_an.Tick += TimerAnimacao_Tick;

            txt_user.GotFocus += TxtUser_GotFocus;
            txt_user.LostFocus += TxtUser_LostFocus;
        }

        private void JogarDoom()
        {
            try
            {
                Doom telaDoom = new Doom();
                telaDoom.Show();
                this.Hide();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Erro ao iniciar o DOOM: " + ex.Message);
            }
        }

        private void TxtUser_GotFocus(object sender, EventArgs e)
        {
            if (txt_user.Text == "Digite seu username")
            {
                txt_user.Text = "";
                txt_user.ForeColor = Color.Black;
            }
        }

        private void TxtUser_LostFocus(object sender, EventArgs e)
        {
            if (string.IsNullOrWhiteSpace(txt_user.Text))
            {
                txt_user.Text = "Digite seu username";
                txt_user.ForeColor = Color.Gray;
            }
        }

        public void ResetarTela()
        {
            lb_resposta.Text = "";
            pt_image_jogo.Image = null;
            grp_resultado.Visible = false;
            cb_cate.Visible = true;
            lb_cate.Visible = true;
            cb_cate.SelectedIndex = -1;
            txt_user.Visible = (modo == "minha_biblioteca");
            btn_jogar.Visible = false;
        }

        private void PreencherCategorias()
        {
            var categorias = _repositorio.ObterCategorias();
            cb_cate.DataSource = null;
            cb_cate.DataSource = new BindingSource(categorias, null);
            cb_cate.DisplayMember = "Nome";
            cb_cate.ValueMember = "Id";
            if (cb_cate.Items.Count > 0)
                cb_cate.SelectedIndex = 0;
        }

        private void btn_bibl_Click_1(object sender, EventArgs e)
        {
            modo = "minha_biblioteca";
            txt_user.Visible = true;
            txt_user.Text = "Digite seu username";
            txt_user.ForeColor = Color.Gray;
            txt_user.Focus();
        }

        private void btn_loja_Click(object sender, EventArgs e)
        {
            modo = "loja";
            txt_user.Visible = false;
        }

        private void btn_sortear_Click(object sender, EventArgs e)
        {
            ResetarTela();
            _titulosAnimacao = null;
            _animIndex = 0;
            _velocidade = 0;
            _jogoSorteado = null;

            if (txt_user.Visible && txt_user.Text == "Digite seu username")
                txt_user.Text = "";


            if (string.IsNullOrWhiteSpace(modo))
            {
                MessageBox.Show("Escolha primeiro 'Minha Biblioteca' ou 'Toda Loja'.");
                return;
            }

            if (modo == "minha_biblioteca")
            {
                string usuario = txt_user.Text.Trim();
                if (string.IsNullOrWhiteSpace(usuario))
                {
                    MessageBox.Show("Digite o nome do usuário para buscar a biblioteca.");
                    return;
                }

                if (!_repositorio.UsuarioExiste(usuario))
                {
                    MessageBox.Show("Usuário não existe.");
                    return;
                }

                if (!_repositorio.UsuarioPossuiJogos(usuario))
                {
                    MessageBox.Show("Este usuário não possui jogos para sortear.");
                    return;
                }
            }

            int idCategoriaSelecionada = 0;
            if (cb_cate.SelectedItem != null)
            {
                var categoriaSelecionada = (Categoria)cb_cate.SelectedItem;
                idCategoriaSelecionada = categoriaSelecionada.Id;
            }

            _jogoSorteado = _repositorio.SortearJogo(modo, txt_user.Text.Trim(), idCategoriaSelecionada);
            if (_jogoSorteado == null)
            {
                MessageBox.Show("Nenhum jogo encontrado.");
                return;
            }

            cb_cate.Visible = false;
            lb_cate.Visible = false;

            _titulosAnimacao = new List<string>() { "Sorteando... 5", "Sorteando... 4", "Sorteando... 3", "Sorteando... 2", "Sorteando... 1" };

            _animIndex = 0;
            _velocidade = 300;
            timer_an.Interval = _velocidade;
            timer_an.Start();

            grp_resultado.Visible = true;
            lb_resposta.Text = "";
            pt_image_jogo.Image = null;
            cb_cate.Visible = false;
            lb_cate.Visible = false;
        }

        private async void TimerAnimacao_Tick(object sender, EventArgs e)
        {
            if (_animIndex < _titulosAnimacao.Count)
            {
                lb_resposta.Text = _titulosAnimacao[_animIndex];
                _animIndex++;
            }
            else
            {
                timer_an.Stop();

                lb_resposta.Text = "🎮 " + _jogoSorteado.Titulo;

                if (!string.IsNullOrWhiteSpace(_jogoSorteado.Titulo) &&
                    _jogoSorteado.Titulo.Trim().Equals("Doom Eternal", StringComparison.OrdinalIgnoreCase))
                {
                    btn_jogar.Visible = true;
                }
                else
                {
                    btn_jogar.Visible = false;
                }

                if (!string.IsNullOrWhiteSpace(_jogoSorteado.Imagem))
                {
                    string url = _jogoSorteado.Imagem.Trim();

                    try
                    {
                        if (_cacheImagens.ContainsKey(url))
                        {
                            pt_image_jogo.Image = _cacheImagens[url];
                        }
                        else
                        {
                            using (var wc = new WebClient())
                            {
                                byte[] data = wc.DownloadData(url);
                                using (var ms = new System.IO.MemoryStream(data))
                                {
                                    Image img = Image.FromStream(ms);
                                    pt_image_jogo.Image = img;
                                    pt_image_jogo.SizeMode = PictureBoxSizeMode.Zoom;
                                    _cacheImagens[url] = img;
                                }
                            }
                        }
                    }
                    catch
                    {
                        pt_image_jogo.Image = null;
                    }
                }
            }
        }



        private void btn_nova_Click(object sender, EventArgs e)
        {
            ResetarTela();
            modo = "";
            cb_cate.SelectedIndex = -1;
            txt_user.Text = "Digite seu username";
            txt_user.ForeColor = Color.Gray;
        }

        private void btn_trailer_Click(object sender, EventArgs e)
        {
            if (_jogoSorteado == null)
            {
                MessageBox.Show("Nenhum jogo foi sorteado ainda!");
                return;
            }

            if (string.IsNullOrWhiteSpace(_jogoSorteado.Trailer))
            {
                MessageBox.Show("Este jogo não possui trailer cadastrado.");
                return;
            }

            Trailer telaTrailer = new Trailer(_jogoSorteado.Trailer, this);
            telaTrailer.Show();
            this.Hide();
        }
        private void pt_image_jogo_Click(object sender, EventArgs e)
        {

        }

        private void lb_resposta_Click(object sender, EventArgs e)
        {

        }

        private void txt_user_TextChanged(object sender, EventArgs e)
        {

        }

        private void Sorteador_Load(object sender, EventArgs e)
        {
        }

        private void btn_jogar_Click(object sender, EventArgs e)
        {
            try
            {

                string caminhoDoom = Path.Combine(
                    Application.StartupPath,
                    @"..\..\..\managed-doom-master\managed-doom-master\ManagedDoom\bin\Debug\net8.0\managed-doom.exe"
                );

                caminhoDoom = Path.GetFullPath(caminhoDoom);

                if (!File.Exists(caminhoDoom))
                {
                    MessageBox.Show("O executável do Doom não foi encontrado em:\n" + caminhoDoom,
                        "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    return;
                }


                Process process = new Process();
                process.StartInfo.FileName = caminhoDoom;
                process.EnableRaisingEvents = true;


                process.Exited += (sender2, e2) =>
                {
                    this.Invoke((MethodInvoker)delegate
                    {
                        btn_jogar.Visible = false;
                        this.Show(); 
                        MessageBox.Show("Jogo finalizado! Voltando ao sorteador.");
                    });
                };


                process.Start();

                this.Hide();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Erro ao iniciar o jogo: " + ex.Message);
            }
        }
    }
}