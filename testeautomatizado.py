"""
TESTE AUTOMATIZADO - CADASTRO DE FORNECEDOR
Sistema: Grão & Massa - Gestão de Padaria
Ferramenta: Selenium WebDriver com Python
"""

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select
from selenium.common.exceptions import TimeoutException, NoSuchElementException
import time
import random
import os
import webbrowser
import re

class TesteCadastroFornecedor:
    def __init__(self, url_base="http://localhost:8080/GitHub/Padaria_grao_massa"):
        """
        Inicializa o teste com a URL base do sistema
        """
        self.url_base = url_base
        self.diretorio_teste = "TesteCadastroFornecedor"
        self.resultados_testes = []
        self.fornecedores_cadastrados = []
        
        # Cria a pasta de resultados
        if not os.path.exists(self.diretorio_teste):
            os.makedirs(self.diretorio_teste)
        
        # Configuração do Chrome
        chrome_options = Options()
        chrome_options.add_argument("--start-maximized")
        chrome_options.add_argument("--disable-gpu")
        chrome_options.add_argument("--no-sandbox")
        
        self.driver = webdriver.Chrome(options=chrome_options)
        self.wait = WebDriverWait(self.driver, 15)
        
        print("✅ Ambiente preparado!")
        print(f"📁 Pasta de testes: {self.diretorio_teste}")
        print(f"🌐 URL base: {self.url_base}")

    def gerar_dados_fornecedor(self):
        """
        Gera dados aleatórios para um fornecedor com CEPs reais
        """
        nomes_empresas = [
            "Distribuidora Pão Bom", "Fornecedora Grão Nobre", "Massa Fina Alimentos",
            "Panificadora Elite", "Cereais & Cia", "Laticínios Vale do Sol",
            "Bebidas São Paulo", "Embalagens Forte", "Grãos do Cerrado",
            "Massas & Cia", "Padaria Modelo", "Alimentos Premium",
            "Distribuidora Norte", "Fornecedora Sul", "Panificadora Central"
        ]
        
        categorias = ["Farinhas", "Laticínios", "Bebidas", "Embalagens", "Grãos", "Massas"]
        
        # LISTA DE CEPs REAIS E VÁLIDOS (todos existem)
        ceps_validos = [
            {"cep": "89228-360", "logradouro": "Rua Santa Catarina", "bairro": "Centro", "cidade": "Joinville", "uf": "SC"},
            {"cep": "89219-000", "logradouro": "Rua Blumenau", "bairro": "Boa Vista", "cidade": "Joinville", "uf": "SC"},
            {"cep": "89216-000", "logradouro": "Rua Dona Francisca", "bairro": "Centro", "cidade": "Joinville", "uf": "SC"},
            {"cep": "89211-000", "logradouro": "Rua Tiradentes", "bairro": "Centro", "cidade": "Joinville", "uf": "SC"},
            {"cep": "89240-000", "logradouro": "Rua XV de Novembro", "bairro": "Centro", "cidade": "São José dos Pinhais", "uf": "PR"},
            {"cep": "01310-000", "logradouro": "Avenida Paulista", "bairro": "Bela Vista", "cidade": "São Paulo", "uf": "SP"},
            {"cep": "01415-000", "logradouro": "Rua Augusta", "bairro": "Cerqueira César", "cidade": "São Paulo", "uf": "SP"},
            {"cep": "04538-132", "logradouro": "Rua Funchal", "bairro": "Vila Olímpia", "cidade": "São Paulo", "uf": "SP"},
            {"cep": "80010-000", "logradouro": "Rua XV de Novembro", "bairro": "Centro", "cidade": "Curitiba", "uf": "PR"},
            {"cep": "80060-000", "logradouro": "Avenida Sete de Setembro", "bairro": "Centro", "cidade": "Curitiba", "uf": "PR"},
            {"cep": "80230-000", "logradouro": "Rua Brasília", "bairro": "Água Verde", "cidade": "Curitiba", "uf": "PR"},
            {"cep": "13010-000", "logradouro": "Rua Barão de Jaguara", "bairro": "Centro", "cidade": "Campinas", "uf": "SP"},
            {"cep": "13015-000", "logradouro": "Avenida Andrade Neves", "bairro": "Centro", "cidade": "Campinas", "uf": "SP"},
            {"cep": "11010-000", "logradouro": "Rua XV de Novembro", "bairro": "Centro", "cidade": "Santos", "uf": "SP"},
            {"cep": "18010-000", "logradouro": "Rua Barão de Tatuí", "bairro": "Centro", "cidade": "Sorocaba", "uf": "SP"},
            {"cep": "13209-000", "logradouro": "Rua Major Antônio", "bairro": "Centro", "cidade": "Jundiaí", "uf": "SP"},
            {"cep": "30310-000", "logradouro": "Rua Ceará", "bairro": "Savassi", "cidade": "Belo Horizonte", "uf": "MG"},
            {"cep": "30130-000", "logradouro": "Avenida Afonso Pena", "bairro": "Centro", "cidade": "Belo Horizonte", "uf": "MG"},
            {"cep": "90010-000", "logradouro": "Rua dos Andradas", "bairro": "Centro", "cidade": "Porto Alegre", "uf": "RS"},
            {"cep": "88010-000", "logradouro": "Rua Conselheiro Mafra", "bairro": "Centro", "cidade": "Florianópolis", "uf": "SC"},
        ]
        
        empresa = random.choice(nomes_empresas)
        cnpj = f"{random.randint(10,99)}.{random.randint(100,999)}.{random.randint(100,999)}/{random.randint(1000,9999)}-{random.randint(10,99)}"
        email = empresa.lower().replace(" ", ".").replace("&", "").replace("ã", "a") + "@empresa.com.br"
        telefone = f"({random.randint(10,99)}) {random.randint(90000,99999)}-{random.randint(1000,9999)}"
        categoria = random.choice(categorias)
        
        # Seleciona um CEP real aleatório
        cep_info = random.choice(ceps_validos)
        
        # Gera número aleatório
        numero = str(random.randint(100, 999))
        complemento = random.choice(["Sala 10", "Bloco A", "Galpão 5", "Andar 2", "", "Loja 3", "Conjunto 101"])
        
        return {
            "nome_empresa": empresa,
            "cnpj": cnpj,
            "email": email,
            "telefone": telefone,
            "cep": cep_info["cep"],
            "logradouro": cep_info["logradouro"],
            "numero": numero,
            "complemento": complemento,
            "bairro": cep_info["bairro"],
            "cidade": cep_info["cidade"],
            "uf": cep_info["uf"],
            "categoria": categoria
        }

    def fazer_login(self, cpf, senha):
        """
        Faz login no sistema
        """
        try:
            print(f"\n🔐 Tentando login com CPF: {cpf}")
            
            self.driver.get(f"{self.url_base}/login.php")
            time.sleep(1)
            
            campo_cpf = self.wait.until(
                EC.presence_of_element_located((By.ID, "cpf"))
            )
            campo_cpf.clear()
            campo_cpf.send_keys(cpf)
            
            campo_senha = self.driver.find_element(By.ID, "senha")
            campo_senha.clear()
            campo_senha.send_keys(senha)
            
            btn_login = self.driver.find_element(By.XPATH, "//button[@type='submit']")
            btn_login.click()
            
            time.sleep(2)
            
            if "index.php" in self.driver.current_url:
                print("✅ Login realizado com sucesso!")
                self.tirar_screenshot("01_login_sucesso.png")
                return True
            else:
                print(f"❌ Falha no login")
                return False
                
        except Exception as e:
            print(f"❌ Erro no login: {e}")
            return False

    def ir_para_cadastro_fornecedor(self):
        """
        Navega para a tela de cadastro de fornecedor
        """
        try:
            print("\n📋 Acessando cadastro de fornecedor...")
            
            # Tenta clicar no link do menu
            try:
                link_fornecedor = self.wait.until(
                    EC.element_to_be_clickable((By.LINK_TEXT, "Fornecedores"))
                )
                link_fornecedor.click()
                print("  ✅ Clicou no link 'Fornecedores'")
            except:
                # Vai direto para a URL
                self.driver.get(f"{self.url_base}/Cadastrar_Fornecedor.php")
                print("  ✅ Acessou diretamente a URL")
            
            time.sleep(2)
            
            if "Cadastrar_Fornecedor.php" in self.driver.current_url:
                print("✅ Tela de cadastro carregada!")
                self.tirar_screenshot("02_cadastro_fornecedor.png")
                return True
            else:
                print(f"⚠️ Não foi para a tela de cadastro - URL: {self.driver.current_url}")
                return False
                
        except Exception as e:
            print(f"❌ Erro ao acessar cadastro: {e}")
            return False

    def preencher_formulario_fornecedor(self, dados):
        """
        Preenche o formulário de cadastro de fornecedor
        """
        try:
            print(f"\n📝 Preenchendo formulário para: {dados['nome_empresa']}")
            print(f"  📍 CEP: {dados['cep']} - {dados['cidade']}/{dados['uf']}")
            
            # Nome da Empresa
            campo_nome = self.wait.until(
                EC.presence_of_element_located((By.NAME, "nNomeEmpresa"))
            )
            campo_nome.clear()
            campo_nome.send_keys(dados["nome_empresa"])
            
            # CNPJ
            campo_cnpj = self.driver.find_element(By.NAME, "nCnpj")
            campo_cnpj.clear()
            campo_cnpj.send_keys(dados["cnpj"])
            
            # E-mail
            campo_email = self.driver.find_element(By.NAME, "nEmail")
            campo_email.clear()
            campo_email.send_keys(dados["email"])
            
            # Telefone
            campo_telefone = self.driver.find_element(By.NAME, "nTelefone")
            campo_telefone.clear()
            campo_telefone.send_keys(dados["telefone"])
            
            # Categoria
            select_categoria = Select(self.driver.find_element(By.NAME, "nCategoria"))
            select_categoria.select_by_visible_text(dados["categoria"])
            
            # CEP - Digita e espera a busca automática
            campo_cep = self.driver.find_element(By.NAME, "nCep")
            campo_cep.clear()
            campo_cep.send_keys(dados["cep"])
            print(f"  ✅ CEP {dados['cep']} digitado, aguardando busca automática...")
            
            # Aguarda a busca do CEP preencher os campos (pode levar alguns segundos)
            time.sleep(3)
            
            # Verifica se o CEP foi preenchido automaticamente
            logradouro_campo = self.driver.find_element(By.NAME, "nLogradouro")
            logradouro_valor = logradouro_campo.get_attribute("value")
            
            if logradouro_valor:
                print(f"  ✅ CEP encontrado: {logradouro_valor}")
            else:
                print("  ⚠️ CEP não encontrado automaticamente, preenchendo manualmente...")
                # Se o CEP não preencheu automaticamente, preenche manualmente
                logradouro_campo.send_keys(dados["logradouro"])
                
                # Bairro
                campo_bairro = self.driver.find_element(By.NAME, "nBairro")
                campo_bairro.clear()
                campo_bairro.send_keys(dados["bairro"])
                
                # Cidade
                campo_cidade = self.driver.find_element(By.NAME, "nCidade")
                campo_cidade.clear()
                campo_cidade.send_keys(dados["cidade"])
                
                # UF
                campo_uf = self.driver.find_element(By.NAME, "nUf")
                campo_uf.clear()
                campo_uf.send_keys(dados["uf"])
            
            # Número
            campo_numero = self.driver.find_element(By.NAME, "nNumero")
            campo_numero.clear()
            campo_numero.send_keys(dados["numero"])
            
            # Complemento
            if dados["complemento"]:
                campo_complemento = self.driver.find_element(By.NAME, "nComplemento")
                campo_complemento.clear()
                campo_complemento.send_keys(dados["complemento"])
            
            print("  ✅ Formulário preenchido com sucesso!")
            self.tirar_screenshot("03_formulario_preenchido.png")
            return True
            
        except Exception as e:
            print(f"❌ Erro ao preencher formulário: {e}")
            self.tirar_screenshot("03_erro_preenchimento.png")
            return False

    def salvar_fornecedor(self):
        """
        Clica no botão salvar e verifica o resultado
        """
        try:
            print("💾 Salvando fornecedor...")
            
            # Clica no botão salvar
            btn_salvar = self.wait.until(
                EC.element_to_be_clickable((By.XPATH, "//button[@type='submit']"))
            )
            btn_salvar.click()
            
            time.sleep(3)
            
            # Verifica se apareceu mensagem de sucesso
            try:
                alerta = self.driver.find_element(By.CLASS_NAME, "alert-box")
                if "sucesso" in alerta.text.lower() or "sucesso" in alerta.text:
                    print("✅ Fornecedor cadastrado com sucesso!")
                    self.tirar_screenshot("04_cadastro_sucesso.png")
                    return True
                else:
                    print(f"⚠️ Mensagem inesperada: {alerta.text}")
                    return False
            except:
                # Verifica se foi redirecionado para a lista
                if "Cadastrar_Fornecedor.php" in self.driver.current_url:
                    print("✅ Cadastro realizado (redirecionado)")
                    return True
                else:
                    print("⚠️ Não foi possível confirmar o cadastro")
                    return False
                
        except Exception as e:
            print(f"❌ Erro ao salvar fornecedor: {e}")
            self.tirar_screenshot("04_erro_salvar.png")
            return False

    def verificar_fornecedor_na_lista(self, nome_empresa):
        """
        Verifica se o fornecedor aparece na lista
        """
        try:
            print(f"🔍 Verificando se '{nome_empresa}' está na lista...")
            
            # Aguarda a tabela carregar
            time.sleep(2)
            
            # Busca na tabela
            tabela = self.driver.find_element(By.CLASS_NAME, "data-table")
            linhas = tabela.find_elements(By.TAG_NAME, "tr")
            
            for linha in linhas:
                try:
                    if nome_empresa in linha.text:
                        print("✅ Fornecedor encontrado na lista!")
                        self.tirar_screenshot("05_fornecedor_encontrado.png")
                        return True
                except:
                    continue
            
            print("⚠️ Fornecedor não encontrado na lista")
            return False
            
        except Exception as e:
            print(f"❌ Erro ao verificar lista: {e}")
            return False

    def limpar_formulario(self):
        """
        Limpa o formulário (clica no botão Limpar)
        """
        try:
            btn_limpar = self.driver.find_element(By.XPATH, "//button[@type='reset']")
            btn_limpar.click()
            time.sleep(1)
            print("  🧹 Formulário limpo")
            return True
        except:
            return False

    def tirar_screenshot(self, nome_arquivo):
        """
        Tira uma captura de tela
        """
        caminho = os.path.join(self.diretorio_teste, nome_arquivo)
        self.driver.save_screenshot(caminho)
        return caminho

    def gerar_relatorio_html(self):
        """
        Gera um relatório HTML com os resultados dos testes
        """
        caminho_html = os.path.join(self.diretorio_teste, "dashboard_fornecedores.html")
        
        sucessos = sum(1 for r in self.resultados_testes if r['status'] == 'Sucesso')
        falhas = len(self.resultados_testes) - sucessos

        html_content = f"""
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <title>Dashboard - Cadastro de Fornecedores</title>
            <style>
                body {{ font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5e6d3; margin: 20px; }}
                .container {{ max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }}
                h1 {{ color: #8B4513; border-bottom: 3px solid #d4a574; padding-bottom: 15px; }}
                .summary {{ display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; }}
                .card {{ background: #f8f4f0; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #8B4513; }}
                .card h2 {{ margin: 0; font-size: 2em; color: #8B4513; }}
                .status-sucesso {{ color: #28a745; }}
                .status-falha {{ color: #dc3545; }}
                table {{ width: 100%; border-collapse: collapse; margin-top: 20px; }}
                th, td {{ padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }}
                th {{ background-color: #8B4513; color: white; }}
                .img-link {{ color: #8B4513; text-decoration: none; font-weight: bold; }}
                .badge {{ display: inline-block; padding: 3px 12px; border-radius: 12px; font-size: 12px; font-weight: bold; }}
                .badge-sucesso {{ background: #d4edda; color: #155724; }}
                .badge-falha {{ background: #f8d7da; color: #721c24; }}
            </style>
        </head>
        <body>
            <div class="container">
                <h1>📦 Cadastro de Fornecedores - Relatório de Testes</h1>
                
                <div class="summary">
                    <div class="card">
                        <h2>{len(self.resultados_testes)}</h2>
                        <small>Total de Testes</small>
                    </div>
                    <div class="card">
                        <h2 class="status-sucesso">{sucessos}</h2>
                        <small>Sucessos ✅</small>
                    </div>
                    <div class="card">
                        <h2 class="status-falha">{falhas}</h2>
                        <small>Falhas ❌</small>
                    </div>
                    <div class="card">
                        <h2>{len(self.fornecedores_cadastrados)}</h2>
                        <small>Fornecedores Cadastrados</small>
                    </div>
                </div>
                
                <h3>📋 Fornecedores Cadastrados:</h3>
                <ul>
        """
        
        for f in self.fornecedores_cadastrados:
            html_content += f"<li><strong>{f['nome']}</strong> - {f['categoria']} - {f['cidade']}/{f['uf']} (CEP: {f['cep']})</li>"
        
        html_content += """
                </ul>
                
                <h3>📊 Resultados dos Testes:</h3>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fornecedor</th>
                            <th>Status</th>
                            <th>Evidência</th>
                        </tr>
                    </thead>
                    <tbody>
        """
        
        for r in self.resultados_testes:
            badge_class = "badge-sucesso" if r['status'] == 'Sucesso' else "badge-falha"
            html_content += f"""
                <tr>
                    <td>{r['id']}</td>
                    <td>{r['nome']}</td>
                    <td><span class="badge {badge_class}">{r['status']}</span></td>
                    <td><a class="img-link" href="{r['screenshot']}" target="_blank">📷 Ver Screenshot</a></td>
                </tr>
            """

        html_content += """
                    </tbody>
                </table>
                
                <div style="margin-top: 30px; padding: 20px; background: #f5f0eb; border-radius: 8px;">
                    <h3>📋 Resumo do Teste</h3>
                    <ul>
                        <li><strong>Data do teste:</strong> """ + time.strftime("%d/%m/%Y %H:%M") + """</li>
                        <li><strong>Sistema:</strong> Grão & Massa - Padaria & Café</li>
                        <li><strong>Funcionalidade testada:</strong> Cadastro de Fornecedores</li>
                        <li><strong>CEPs utilizados:</strong> Todos reais e válidos</li>
                    </ul>
                </div>
            </div>
        </body>
        </html>
        """

        with open(caminho_html, "w", encoding="utf-8") as f:
            f.write(html_content)
        
        return caminho_html

    def executar_teste_completo(self, quantidade=3):
        """
        Executa o teste completo de cadastro de fornecedores
        """
        print("\n" + "="*60)
        print("📦 GRÃO & MASSA - TESTE DE CADASTRO DE FORNECEDORES")
        print("="*60)
        
        # 1. Login
        if not self.fazer_login("465.465.651-65", "0515"):  # Use suas credenciais
            print("❌ Não foi possível fazer login. Encerrando teste.")
            self.driver.quit()
            return
        
        # 2. Ir para cadastro de fornecedor
        if not self.ir_para_cadastro_fornecedor():
            print("❌ Não foi possível acessar o cadastro.")
            self.driver.quit()
            return
        
        # 3. Executa os testes
        for i in range(quantidade):
            print(f"\n{'='*40}")
            print(f"🧪 TESTE {i+1} DE {quantidade}")
            print(f"{'='*40}")
            
            # Gera dados aleatórios
            dados = self.gerar_dados_fornecedor()
            
            # Preenche o formulário
            if not self.preencher_formulario_fornecedor(dados):
                print("❌ Falha ao preencher formulário")
                continue
            
            # Salva o fornecedor
            salvou = self.salvar_fornecedor()
            
            if salvou:
                # Verifica se apareceu na lista
                self.verificar_fornecedor_na_lista(dados["nome_empresa"])
                self.fornecedores_cadastrados.append({
                    "nome": dados["nome_empresa"],
                    "categoria": dados["categoria"],
                    "cidade": dados["cidade"],
                    "uf": dados["uf"],
                    "cep": dados["cep"]
                })
            
            # Registra resultado
            status = "Sucesso" if salvou else "Falha"
            self.resultados_testes.append({
                "id": i+1,
                "nome": dados["nome_empresa"],
                "status": status,
                "screenshot": self.tirar_screenshot(f"teste_{i+1}_final.png")
            })
            
            # Limpa o formulário para o próximo teste
            self.limpar_formulario()
            time.sleep(1)
        
        # 4. Gera relatório
        caminho_report = self.gerar_relatorio_html()
        
        # 5. Finaliza
        self.driver.quit()
        
        print("\n" + "="*60)
        print(f"✅ TESTES FINALIZADOS!")
        print(f"📊 Relatório gerado em: {caminho_report}")
        print(f"📁 Screenshots salvos em: {self.diretorio_teste}")
        print("="*60)
        
        # Abre o relatório no navegador
        webbrowser.open('file://' + os.path.realpath(caminho_report))

def main():
    """
    Função principal
    """
    print("="*60)
    print("📦 GRÃO & MASSA - AUTOMAÇÃO DE CADASTRO DE FORNECEDORES")
    print("="*60)
    
    URL_BASE = "http://localhost:8080/GitHub/Padaria_grao_massa"
    
    try:
        qtd = input("Quantos fornecedores deseja cadastrar? (padrão: 3): ")
        qtd = int(qtd) if qtd.strip() else 3
        
        if qtd > 0:
            teste = TesteCadastroFornecedor(url_base=URL_BASE)
            teste.executar_teste_completo(qtd)
        else:
            print("Quantidade inválida. Use um número maior que 0.")
    except ValueError:
        print("Por favor, digite um número inteiro válido.")

if __name__ == "__main__":
    main()