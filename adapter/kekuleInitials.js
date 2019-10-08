/**
 * Created by ginger on 2017/3/1.
 */
var int_counter = 0;
function initKekule(){
    int_counter++;
    if(typeof Kekule !== "undefined") {
        if(typeof Kekule.Widget !== "undefined") {
            if (Kekule.Widget.AutoLauncher)
            {
                    Kekule.Widget.AutoLauncher.deferring = true;
            }

            if (Kekule.globalOptions && Kekule.ChemWidget)
            {
                    var BNS = Kekule.ChemWidget.ComponentWidgetNames;
                    Kekule.globalOptions.add('chemWidget.composer.chemToolButtons',
                            [
                                    BNS.manipulate,
                                    BNS.erase,
                                    {'name': BNS.molBond, 'attached': [
                                            BNS.molBondSingle, BNS.molBondDouble, BNS.molBondTriple,
                                            BNS.molBondWedgeUp, BNS.molBondWedgeDown,
                                            BNS.molChain,
                                            BNS.trackInput,				
                                            BNS.molRepFischer1, BNS.molRepFischer2,
                                            BNS.molRepSawhorseStaggered, BNS.molRepSawhorseEclipsed
                                    ]},
                                    BNS.molAtomAndFormula,			
                                    {'name': BNS.molRing, 'attached': [
                                            BNS.molRing3, BNS.molRing4, BNS.molRing5, BNS.molRing6, 
                                            BNS.molFlexRing, 
                                            BNS.molRingAr6,
                                            BNS.molRepCyclopentaneHaworth1, 
                                            BNS.molRepCyclohexaneHaworth1, 
                                            BNS.molRepCyclohexaneChair1, BNS.molRepCyclohexaneChair2
                                    ]},
                                    BNS.molCharge,
                                    BNS.glyph,
                                    BNS.textImage
                            ]);

                    var SM = Kekule.ObjPropSettingManager;
                    if (SM)
                    {
                            var EMC = Kekule.Editor.ObjModifier.Category;
                            // overwrite molOnly setting of composer
                            SM.register('Kekule.Editor.Composer.molOnly', {  // composer that can only edit molecule
                                    enableStyleToolbar: true,
                                    enableOperHistor: true,
                                    enableLoadNewFile: true,
                                    enableCreateNewDoc: true,
                                    allowCreateNewChild: true,
                                    commonToolButtons: null,   // create all default common tool buttons
                                    chemToolButtons: [
                                            BNS.manipulate,
                                            BNS.erase,
                                            {'name': BNS.molBond, 'attached': [
                                                    BNS.molBondSingle, BNS.molBondDouble, BNS.molBondTriple,
                                                     BNS.molBondWedgeUp, BNS.molBondWedgeDown,
                                                    BNS.molChain,
                                                    BNS.trackInput,
                                                    BNS.molRepFischer1, BNS.molRepFischer2,
                                                    BNS.molRepSawhorseStaggered, BNS.molRepSawhorseEclipsed
                                            ]},
                                            BNS.molAtom,
                                            {'name': BNS.molRing, 'attached': [
                                                    BNS.molRing3, BNS.molRing4, BNS.molRing5, BNS.molRing6, 
                                                    BNS.molFlexRing, 
                                                    BNS.molRingAr6,
                                                    BNS.molRepCyclopentaneHaworth1,
                                                    BNS.molRepCyclohexaneHaworth1,
                                                    BNS.molRepCyclohexaneChair1, BNS.molRepCyclohexaneChair2
                                            ]},
                                            BNS.molCharge
                                    ],   // create only chem tool buttons related with molecule
                                    styleToolComponentNames: null,  // create all default style components
                                    allowedObjModifierCategories: [EMC.GENERAL, EMC.CHEM_STRUCTURE]  // only all chem structure modifiers
                            });
                    }

                    window.interpreter = Kekule.ChemWidget.ChemObjInserter = new Kekule.ChemWidget.ChemObjInserter(document);
                    window.interpreter.renderConfigs.colorConfigs.useAtomSpecifiedColor=settings_kekule_atomspecifiedcolor;
                    window.interpreter.renderConfigs.moleculeDisplayConfigs.defChargeMarkType=settings_kekule_chargemarktype;
            }
        } else {
            if (int_counter < 50) {
                setTimeout(initKekule, 250);
            }
        }
    } else {
        if (int_counter < 50) {
            setTimeout(initKekule, 250);
        }
    }
}

initKekule();