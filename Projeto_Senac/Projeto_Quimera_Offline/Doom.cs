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
        private Thread? _doomThread;

        public Doom()
        {
            InitializeComponent();
            this.Text = "DOOM Eternal (modo clássico)";
            this.Width = 1024;
            this.Height = 768;
        }

        private void Doom_Load(object sender, EventArgs e)
        {
            RunDoom();
        }

        private void RunDoom()
        {
            try
            {
                string wadPath = Path.Combine(Application.StartupPath, "WADs", "doom1.wad");

                // 🔥 COLOCA AQUI
                this.Invoke((MethodInvoker)(() =>
                {
                    MessageBox.Show("Caminho do WAD:\n" + wadPath);
                }));

                if (!File.Exists(wadPath))
                {
                    this.Invoke((MethodInvoker)(() =>
                    {
                        MessageBox.Show($"Arquivo WAD não encontrado em:\n{wadPath}");
                    }));
                    return;
                }

                Environment.SetEnvironmentVariable("DOOMWADDIR", Path.GetDirectoryName(wadPath));

                ManagedDoom.Silk.SilkProgram.Main(new string[0]);
            }
            catch (Exception ex)
            {
                this.Invoke((MethodInvoker)(() =>
                {
                    MessageBox.Show("Erro ao iniciar o Doom: " + ex.Message);
                }));
            }
        }

        private void Doom_FormClosed(object sender, FormClosedEventArgs e)
        {
            try
            {
                if (_doomThread != null && _doomThread.IsAlive)
                    _doomThread.Interrupt();

                Form sorteador = Application.OpenForms.OfType<Sorteador>().FirstOrDefault();
                if (sorteador != null)
                    sorteador.Show();
            }
            catch { }
        }
    }
}
