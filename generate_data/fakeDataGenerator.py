"""
INSERT INTO `merchant` (`raisonSociale`, `siren`, `currency`, `numCarte`, `network`, `password`, `idLogin`) VALUES
('Action Contre la faim', '123456789', 'EUR', '5879', 'AE', '$2y$10$wIvqO7K4DK8alKhpDDzVYOXmySi6zvEH80xEc0yQ6lJZsfcURaqoy', 'action'),
('Louis Vuitton Services', '347662454', 'USD', '4589', 'MC', '$2y$10$rJJzcYpZ7TPh.uRl2ix7f.umGB3oykXjKFFDIYXI2ZYIY1R40eAaS', 'louisvi'),
('Leroy Merlin Noisy', '384560942', 'EUR', '7485', 'AE', '$2y$10$eLydEkQ3bS7p3ciLX5kL.OxxOtQR2B782ZylCXBTbsZ7RyyNn4vo6', 'leroy'),
('Gucci France', '632032348', 'EUR', '9685', 'VS', '$2y$10$rN7DbLVNgXvknxD4HiliXOKHOIhZ88vswLlRvcDsAFFPQtYMnfk.2', 'guccifrance'),
('McDonald Champs sur Marne', '722003936', 'EUR', '1796', 'VS', '$2y$10$y8XN1Ye0fIknpPV6lpQCAeOE8H2.HrRyFgTYkmvpUr2KIOiUxFNaC', 'mcdo'),
('Burger King', '987654321', 'USD', '8565', 'AE', '$2y$10$0id6ZFNVDu8/gPB8DbmEd.cyJqtPTWk48xYxO.jbq76dY6LgXwSUS', 'bk');


INSERT INTO `transaction` (`idTransaction`, `numAuthorization`, `dateTransaction`, `endingFoursCardNumbers`, `currency`, `numSiren`, `amount`) VALUES
(1, 'D5F689', '2022-09-15 12:11:30', '1245', 'EUR', '384560942', 300000),
(2, 'B735S5', '2017-10-10 23:29:46', '1488', 'EUR', '722003936', 740000),
(3, 'PL2593', '2022-07-19 07:35:10', '1458', 'EUR', '722003936', 25000),
(4, 'Y465L2', '2022-11-19 04:31:18', '3657', 'EUR', '722003936', 1200000),
(5, 'UI8568', '2022-08-11 20:10:27', '9856', 'EUR', '632032348', 890000),
(6, '23FD89', '2022-10-13 10:24:16', '4156', 'EUR', '347662454', 213000),
(7, '78MP36', '2022-10-16 10:24:16', '6515', 'EUR', '347662454', 56000),
(8, 'THD4GR', '2022-08-10 13:06:26', '4578', 'EUR', '632032348', 10000),
(9, 'UJLDAE', '2022-07-10 13:06:26', '7458', 'EUR', '632032348', 5400),
(10, 'RFZE48', '2022-06-10 13:07:32', '4512', 'USD', '347662454', 8000),
(11, 'GBT47M', '2022-04-10 13:07:32', '9632', 'USD', '347662454', 5200),
(12, 'DE74RF', '2022-02-12 13:33:52', '4596', 'EUR', '123456789', 1000),
(13, 'GT41DE', '2022-03-12 13:33:52', '7846', 'EUR', '987654321', 2000),
(14, 'D5F596', '2022-01-12 13:33:52', '4596', 'EUR', '123456789', 15000);

INSERT INTO `discount` (`numDiscount`, `numTransaction`, `sens`, `unpaidWording`, `numUnpaidFile`, `dateDiscount`) VALUES
(1, 2, '-', 'Solde insuffisant', '1234', '2022-02-09 08:40:08'),
(2, 3, '+', NULL, NULL, '2022-04-19 07:36:12'),
(3, 1, '+', NULL, NULL, '2022-06-29 13:22:14'),
(11, 6, '-', 'Plafond atteint', 'DP236', '2022-08-16 10:49:45'),
(12, 4, '+', NULL, NULL, '2020-08-01 10:49:45'),
(13, 7, '-', 'Plafond atteint', '2365F', '2022-09-05 11:13:32'),
(14, 6, '+', NULL, NULL, '2022-11-09 09:45:37'),
(15, 7, '+', NULL, NULL, '2022-11-25 16:30:26'),
(16, 5, '+', NULL, NULL, '2022-11-01 14:10:12'),
(17, 10, '-', 'Plafond atteint', 'BAD4A', '2022-01-01 14:10:12'),
(18, 8, '-', 'Solde insuffisant', 'A1FTG', '2022-02-01 21:10:51'),
(19, 9, '-', 'Solde insuffisant', 'RFTYH', '2022-03-01 22:10:35'),
(20, 11, '-', 'Erreur inconnue', 'MPS5V', '2022-04-01 20:10:12'),
(21, 12, '+', NULL, NULL, '2022-11-10 14:58:34'),
(22, 13, '+', NULL, NULL, '2022-11-01 14:58:34'),
(23, 9, '-', 'Erreur inconnue', 'MPS8V', '2022-07-01 14:10:35'),
(24, 14, '-', 'Carde plus valide', 'MPS8V', '2022-01-01 08:10:12');


"""

import random
import time
import string

first_index_transaction = 15
first_index_discount = 25
discountListReasons = ["Solde insuffisant", "Plafond atteint", "Erreur inconnue", "Carde plus valide", "Erreur OTP", "Erreur de saisie", "Problème de connexion"]
list_transaction = []
list_discount = []

def random_date(start, end, prop):
    start = time.mktime(time.strptime(start, '%Y-%m-%d %H:%M:%S'))
    end = time.mktime(time.strptime(end, '%Y-%m-%d %H:%M:%S'))
    p = start + prop * (end - start)
    return time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(p))



#generate 1000 random transactions
for i in range(150):
    numSiren = random.choice(["384560942", "722003936", "632032348", "347662454", "123456789", "987654321"])
    dateTransaction = random_date("2016-01-01 00:00:00", "2022-11-30 00:00:00", random.random())
    endingFoursCardNumbers = str(random.randint(1000, 9999))
    currency = random.choice(["EUR", "USD", "GBP", "JPY", "CHF", "CAD", "AUD", "NZD"])
    amount = random.randint(1000, 100000)
    numAuthorization = ''.join(random.choice(string.ascii_uppercase + string.digits) for _ in range(6))
    
    
    #generate 1 to 3 discounts per transaction
    for j in range(random.randint(1, 3)):
        sens = random.choice(["+", "-"])
        unpaidWording = "NULL"
        if sens == "-":
            unpaidWording = random.choice(discountListReasons)
        numUnpaidFile = ''.join(random.choice(string.ascii_uppercase + string.digits) for _ in range(5))
        dateDiscount = random_date(dateTransaction, "2022-11-30 00:00:00", random.random())
        list_discount.append((first_index_discount, first_index_transaction, sens, unpaidWording, numUnpaidFile, dateDiscount))
        first_index_discount += 1
    list_transaction.append((first_index_transaction, numAuthorization, dateTransaction, endingFoursCardNumbers, currency, numSiren, amount))
    first_index_transaction += 1
    
for e in list_transaction:
    print(e)

print('======================')
for e in list_discount:
    print(e)
    
# write in txt file 
with open('generate_data/transactions.txt', 'w') as f:
    for e in list_transaction:
        # replace 'NULL' by NULL
        f.write(str(e).replace("'NULL'", "NULL") + ",\n")
        

with open('generate_data/discount.txt', 'w') as f:
    for e in list_discount:
        # replace 'NULL' by NULL
        f.write(str(e).replace("'NULL'", "NULL") + ",\n")
        
        

        
        
    

