<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\Downloadable\Test\Unit\Model\Plugin;

class AfterProductLoadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Downloadable\Model\Plugin\AfterProductLoad
     */
    protected $model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $linkRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productExtensionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productExtensionFactory;

    protected function setUp()
    {
        $this->linkRepositoryMock = $this->getMock('\Magento\Downloadable\Api\LinkRepositoryInterface');
        $this->productExtensionFactory = $this->getMockBuilder('\Magento\Catalog\Api\Data\ProductExtensionFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $this->model = new \Magento\Downloadable\Model\Plugin\AfterProductLoad(
            $this->linkRepositoryMock,
            $this->productExtensionFactory
        );
        $this->productMock = $this->getMockBuilder('\Magento\Catalog\Model\Product')
            ->disableOriginalConstructor()
            ->getMock();
        $this->productExtensionMock = $this->getMock('\Magento\Catalog\Api\Data\ProductExtensionInterface');
    }

    public function testAfterLoad()
    {
        $this->productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn(\Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE);

        $this->productExtensionFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->productExtensionMock);

        $linkMock = $this->getMock('\Magento\Downloadable\Api\Data\LinkInterface');
        $this->linkRepositoryMock->expects($this->once())
            ->method('getLinksByProduct')
            ->with($this->productMock)
            ->willReturn([$linkMock]);
        $sampleMock = $this->getMock('\Magento\Downloadable\Api\Data\SampleInterface');
        $this->linkRepositoryMock->expects($this->once())
            ->method('getSamplesByProduct')
            ->with($this->productMock)
            ->willReturn([$sampleMock]);
        $this->productExtensionMock->expects($this->once())
            ->method('setDownloadableProductLinks')
            ->with([$linkMock])
            ->willReturnSelf();
        $this->productExtensionMock->expects($this->once())
            ->method('setDownloadableProductSamples')
            ->with([$sampleMock])
            ->willReturnSelf();
        $this->productMock->expects($this->once())
            ->method('setExtensionAttributes')
            ->with($this->productExtensionMock)
            ->willReturnSelf();

        $this->assertEquals(
            $this->productMock,
            $this->model->afterLoad($this->productMock)
        );
    }

    public function testAfterLoadWithExistingExtensionAttributes()
    {
        $this->productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn(\Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE);
        $this->productMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($this->productExtensionMock);

        $this->productExtensionFactory->expects($this->never())
            ->method('create');

        $linkMock = $this->getMock('\Magento\Downloadable\Api\Data\LinkInterface');
        $this->linkRepositoryMock->expects($this->once())
            ->method('getLinksByProduct')
            ->with($this->productMock)
            ->willReturn([$linkMock]);
        $sampleMock = $this->getMock('\Magento\Downloadable\Api\Data\SampleInterface');
        $this->linkRepositoryMock->expects($this->once())
            ->method('getSamplesByProduct')
            ->with($this->productMock)
            ->willReturn([$sampleMock]);
        $this->productExtensionMock->expects($this->once())
            ->method('setDownloadableProductLinks')
            ->with([$linkMock])
            ->willReturnSelf();
        $this->productExtensionMock->expects($this->once())
            ->method('setDownloadableProductSamples')
            ->with([$sampleMock])
            ->willReturnSelf();
        $this->productMock->expects($this->once())
            ->method('setExtensionAttributes')
            ->with($this->productExtensionMock)
            ->willReturnSelf();

        $this->assertEquals(
            $this->productMock,
            $this->model->afterLoad($this->productMock)
        );
    }

    public function testAfterLoadOnlyLinks()
    {
        $this->productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn(\Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE);

        $this->productExtensionFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->productExtensionMock);

        $linkMock = $this->getMock('\Magento\Downloadable\Api\Data\LinkInterface');
        $this->linkRepositoryMock->expects($this->once())
            ->method('getLinksByProduct')
            ->with($this->productMock)
            ->willReturn([$linkMock]);
        $this->linkRepositoryMock->expects($this->once())
            ->method('getSamplesByProduct')
            ->with($this->productMock)
            ->willReturn(null);
        $this->productExtensionMock->expects($this->once())
            ->method('setDownloadableProductLinks')
            ->with([$linkMock])
            ->willReturnSelf();
        $this->productExtensionMock->expects($this->never())
            ->method('setDownloadableProductSamples');
        $this->productMock->expects($this->once())
            ->method('setExtensionAttributes')
            ->with($this->productExtensionMock)
            ->willReturnSelf();

        $this->assertEquals(
            $this->productMock,
            $this->model->afterLoad($this->productMock)
        );
    }

    public function testAfterLoadOnlySamples()
    {
        $this->productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn(\Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE);

        $this->productExtensionFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->productExtensionMock);

        $this->linkRepositoryMock->expects($this->once())
            ->method('getLinksByProduct')
            ->with($this->productMock)
            ->willReturn(null);
        $sampleMock = $this->getMock('\Magento\Downloadable\Api\Data\SampleInterface');
        $this->linkRepositoryMock->expects($this->once())
            ->method('getSamplesByProduct')
            ->with($this->productMock)
            ->willReturn([$sampleMock]);
        $this->productExtensionMock->expects($this->never())
            ->method('setDownloadableProductLinks');
        $this->productExtensionMock->expects($this->once())
            ->method('setDownloadableProductSamples')
            ->with([$sampleMock])
            ->willReturnSelf();
        $this->productMock->expects($this->once())
            ->method('setExtensionAttributes')
            ->with($this->productExtensionMock)
            ->willReturnSelf();

        $this->assertEquals(
            $this->productMock,
            $this->model->afterLoad($this->productMock)
        );
    }

    public function testAfterLoadIfProductTypeNotDownloadable()
    {
        $this->productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
        $this->productMock->expects($this->never())->method('getExtensionAttributes');
        $this->productMock->expects($this->never())->method('setExtensionAttributes');
        $this->assertEquals(
            $this->productMock,
            $this->model->afterLoad($this->productMock)
        );
    }
}
