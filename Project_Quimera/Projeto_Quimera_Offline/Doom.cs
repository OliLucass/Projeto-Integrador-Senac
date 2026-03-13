using ManagedDoom;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using ManagedDoom;
using System.Windows.Forms;

namespace Projeto_integrador
{
    public partial class Doom : Form
    {
        private Thread _doomThread;

        public Doom()
        {
            InitializeComponent();
            this.Text = "DOOM Eternal (modo clássico)";
            this.Width = 1024;
            this.Height = 768;
        }

        private void Doom_Load(object sender, EventArgs e)
        {
            _doomThread = new Thread(RunDoom);
            _doomThread.Start();
        }

        private void RunDoom()
        {
            try
            {
                string wadPath = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "WADs", "DOOM1.WAD");

                if (!File.Exists(wadPath))
                {
                    MessageBox.Show($"Arquivo WAD não encontrado em:\n{wadPath}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    return;
                }

                string[] args = { "-iwad", wadPath };
                ManagedDoom.Silk.SilkProgram.Main(args);
            }
            catch (Exception ex)
            {
                MessageBox.Show("Erro ao iniciar o Doom: " + ex.Message);
            }
        }

        private void Doom_FormClosed(object sender, FormClosedEventArgs e)
        {
            try
            {
                if (_doomThread != null && _doomThread.IsAlive)
                    _doomThread.Abort();

                Form sorteador = Application.OpenForms.OfType<Sorteador>().FirstOrDefault();
                if (sorteador != null)
                    sorteador.Show();
            }
            catch { }
        }
    }
}
