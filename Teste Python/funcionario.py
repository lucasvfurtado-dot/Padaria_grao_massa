"""
TESTE AUTOMATIZADO - CADASTRO DE FUNCIONÁRIOS (GRÃO & MASSA)
Sistema: Grão & Massa - Padaria & Café
Ferramenta: Selenium WebDriver com Python
"""

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
import time
import random
import os
import webbrowser

class TesteAutomatizadoFuncionario:
    def __init__(self, url_base="http://localhost:8080/padaria_grao_massa/Cadastrar_Funcionario.php"):
        self.url_base = url_base
        
        
        self.diretorio_atual = os.path.dirname(os.path.abspath(__file__))
        self.diretorio_teste = os.path.join(self.diretorio_atual, "TesteCadastroFuncionario")
        
       
        if not os.path.exists(self.diretorio_teste):
            os.makedirs(self.diretorio_teste)
            
       
        self.resultados_testes = []

        chrome_options = Options()
        chrome_options.add_argument("--start-maximized")
        
        self.driver = webdriver.Chrome(options=chrome_options)
        self.wait = WebDriverWait(self.driver, 10)
        
        print("✓ Ambiente preparado e pasta 'TesteCadastroFuncionario' verificada!")

    def gerar_dados_aleatorios(self):
        nomes = ["Victor Silva", "Mariana Costa", "Roberto Almeida", "Fernanda Lima", 
                 "Carlos Eduardo Souza", "Juliana Ramos", "Thiago Gouveia", "Letícia Martins"]
        cargos = ["Padeiro", "Atendente", "Gerente", "Barista", "Caixa", "Confeiteiro"]
        
        nome = random.choice(nomes)
        nome_email = nome.lower().replace(' ', '.').replace('ã', 'a').replace('á', 'a').replace('í', 'i')
        
        return {
            "nNomeCompleto": nome,
            "nCpf": f"{random.randint(100,999)}.{random.randint(100,999)}.{random.randint(100,999)}-{random.randint(10,99)}",
            "nCargo": random.choice(cargos),
            "nEmail": f"{nome_email}@graoemassa.com.br",
            "nTelefone": f"(11) 9{random.randint(1000,9999)}-{random.randint(1000,9999)}",
            "nCep": random.choice(["01001000", "89201000", "80010000"]), 
            "nNumero": str(random.randint(10, 2000)),
            "nComplemento": random.choice(["", "Apto 10", "Fundos", "Bloco B", "Sala 2"])
        }

    def tirar_screenshot(self, nome_arquivo):
        caminho = os.path.join(self.diretorio_teste, nome_arquivo)
        self.driver.save_screenshot(caminho)
        return nome_arquivo

    def gerar_relatorio_html(self):
   
        sucessos = sum(1 for r in self.resultados_testes if r['status'] == 'Sucesso')
        falhas = len(self.resultados_testes) - sucessos

        linhas_html = ""
        for r in self.resultados_testes:
            cor_status = "status-sucesso" if r['status'] == 'Sucesso' else "status-falha"
            linhas_html += f"""
                <tr>
                    <td>{r['id']}</td>
                    <td>{r['nome']}</td>
                    <td>{r['cargo']}</td>
                    <td class="{cor_status}">{r['status']}</td>
                    <td><a class="img-link" href="{r['screenshot']}" target="_blank">Visualizar Screenshot</a></td>
                </tr>
            """

        caminho_junto = os.path.join(self.diretorio_atual, "dashboard_funcionario.html")
        caminho_subpasta = os.path.join(self.diretorio_atual, "TesteCadastroClientes", "dashboard_funcionario.html")
        
        caminho_template_final = ""
        if os.path.exists(caminho_junto):
            caminho_template_final = caminho_junto
        elif os.path.exists(caminho_subpasta):
            caminho_template_final = caminho_subpasta
        else:
            print("❌ Erro: O arquivo 'dashboard_funcionario.html' não foi encontrado na pasta do projeto.")
            return None

        with open(caminho_template_final, "r", encoding="utf-8") as file:
            template = file.read()

        relatorio_final = template.replace("{{TOTAL}}", str(len(self.resultados_testes)))
        relatorio_final = relatorio_final.replace("{{SUCESSOS}}", str(sucessos))
        relatorio_final = relatorio_final.replace("{{FALHAS}}", str(falhas))
        relatorio_final = relatorio_final.replace("{{TABELA_LINHAS}}", linhas_html)

        caminho_html = os.path.join(self.diretorio_teste, "dashboard.html")
        with open(caminho_html, "w", encoding="utf-8") as f:
            f.write(relatorio_final)
        
        return caminho_html

    def executar_teste_completo(self, quantidade):
        for i in range(quantidade):
            print(f"\n🚀 Iniciando cadastro de funcionário {i+1} de {quantidade}...")
            dados = self.gerar_dados_aleatorios()
            status = "Falha"
            nome_print = ""
            
            try:
              
                self.driver.get(self.url_base)
                
               
                self.wait.until(EC.presence_of_element_located((By.NAME, "nNomeCompleto"))).send_keys(dados["nNomeCompleto"])
                self.driver.find_element(By.NAME, "nCpf").send_keys(dados["nCpf"])
                self.driver.find_element(By.NAME, "nCargo").send_keys(dados["nCargo"])
                self.driver.find_element(By.NAME, "nEmail").send_keys(dados["nEmail"])
                self.driver.find_element(By.NAME, "nTelefone").send_keys(dados["nTelefone"])
                
              
                campo_cep = self.driver.find_element(By.NAME, "nCep")
                campo_cep.send_keys(dados["nCep"])
                campo_cep.send_keys(Keys.TAB) 
                
                time.sleep(1.5)
                
                self.driver.find_element(By.NAME, "nNumero").send_keys(dados["nNumero"])
                if dados["nComplemento"]:
                    self.driver.find_element(By.NAME, "nComplemento").send_keys(dados["nComplemento"])
                
                self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
                
                time.sleep(3)
                
                if "msg=sucesso" in self.driver.current_url.lower():
                    status = "Sucesso"
                else:
                    try:
                        alerta = self.driver.find_element(By.CLASS_NAME, "alert-success")
                        if alerta:
                            status = "Sucesso"
                    except:
                        pass
                
                nome_print = self.tirar_screenshot(f"cadastro_func_{i+1}.png")
                
            except Exception as e:
                print(f"   ✗ Erro no processo: {e}")
                nome_print = self.tirar_screenshot(f"cadastro_func_erro_{i+1}.png")
            
            self.resultados_testes.append({
                "id": i+1,
                "nome": dados["nNomeCompleto"],
                "cargo": dados["nCargo"],
                "status": status,
                "screenshot": nome_print
            })

        caminho_report = self.gerar_relatorio_html()
        self.driver.quit()
        
       
        if caminho_report:
            print(f"\n✅ Testes finalizados! Relatório gerado em: {caminho_report}")
            webbrowser.open('file://' + os.path.realpath(caminho_report))
        else:
            print("\n⚠️ Testes finalizados, mas o painel HTML não foi aberto devido à falta do template.")

if __name__ == "__main__":
    print("--- SISTEMA DE AUTOMAÇÃO FUNCIONÁRIOS - GRÃO & MASSA ---")
    try:
        qtd = int(input("Quantos funcionários você deseja cadastrar para teste? "))
        if qtd > 0:

            URL_LOCAL = "http://localhost:8080/padaria_grao_massa/Cadastrar_Funcionario.php"
            
            teste = TesteAutomatizadoFuncionario(url_base=URL_LOCAL)
            teste.executar_teste_completo(qtd)
        else:
            print("Quantidade inválida.")
    except ValueError:
        print("Por favor, digite apenas números inteiros.")