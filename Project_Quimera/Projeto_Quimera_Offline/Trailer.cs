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

namespace Projeto_integrador
{
    public partial class Trailer : Form
    {
        private string _urlTrailer;
        private Sorteador _sorteador; // referência do form sorteador

        public Trailer(string urlTrailer, Sorteador sorteador)
        {
            InitializeComponent();
            _urlTrailer = urlTrailer;
            _sorteador = sorteador;
        }

        private async void Trailer_Load(object sender, EventArgs e)
        {
            await wb_trailer.EnsureCoreWebView2Async(null);
            wb_trailer.Source = new Uri(_urlTrailer);
        }

        private void btn_nova_Click(object sender, EventArgs e)
        {
            // Fecha o trailer
            this.Close();

            // Reexibe o form Sorteador
            _sorteador.Show();

            // Reseta o sorteador para o estado inicial
            _sorteador.ResetarTela();
        }
    }
}